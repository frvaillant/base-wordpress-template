<?php

namespace App\Base\Router;

use App\Base\Router\Utils\Tools;
use Doctrine\Common\Annotations\AnnotationReader as DocReader;
use Doctrine\Common\Annotations\DocParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route as SiteRoute;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RoutesCollector
{
    public const ROUTE_CLASS = 'Symfony\Component\Routing\Annotation\Route';
    public const EXCLUDED_FOLDERS = [
        'vendor', 'assets', 'Async', 'AsyncTasks', 'ChatBundle', 'console', 'DevTools',
        'Entity', 'Form', 'Import', 'Model', 'node_modules', 'public', 'Service',
        'Templates', 'View',
    ];

    private string $folder;
    private RouteCollection $routes;
    private array $routesPaths = [];
    private DocParser $parser;
    private DocReader $reader;
    private array $errors = [];
    protected array $wordpressUrls = [];

    public function __construct()
    {
        $this->initializeComponents();
    }

    private function initializeComponents(): void
    {
        $this->folder = __DIR__ . '/../../';
        $this->routes = new RouteCollection();
        $this->parser = new DocParser();
        $this->reader = new DocReader($this->parser);
    }

    public function getRoutes(): RouteCollection
    {
        $finder = $this->initializeFinder();
        foreach ($finder as $file) {
            $this->processControllerFile($file);
        }

        if ($this->hasErrors()) {
            $this->dispatchErrors();
        }

        return $this->routes;
    }

    private function initializeFinder(): Finder
    {
        $finder = new Finder();
        foreach (self::EXCLUDED_FOLDERS as $folder) {
            $finder->exclude($folder);
        }
        return $finder->files()->in($this->folder)->name('*Controller.php')->notName('AbstractController.php');
    }

    private function processControllerFile($file): void
    {
        $fileName = basename($file->getFilename(), '.php');
        $namespace = $this->extractNamespace($file->getContents());
        $class = $this->getReflectionClass($fileName, $namespace);

        foreach ($class->getMethods() as $method) {
            $this->addRouteFromMethod($method);
        }
    }

    private function extractNamespace($fileContents): string
    {
        preg_match('/(namespace )(.*?)(;)/', $fileContents, $matches);
        return $matches[2] ?? '';
    }

    private function addRouteFromMethod(\ReflectionMethod $method): void
    {
        if ($this->hasValidRoute($method)) {
            $this->validateResponseReturnType($method);
            $route = $this->getRoute($method);
            $arguments = $this->getMethodArguments($method);
            $this->setControllerArguments($method, $route, $arguments);
        }
    }

    private function validateResponseReturnType(\ReflectionMethod $method): void
    {
        if (!$this->matchResponseReturn($method)) {
            throw new \Exception(
                sprintf(
                    'La méthode %s du contrôleur %s ne renvoie pas une réponse valide',
                    $method->getName(),
                    $method->getDeclaringClass()->getName()
                )
            );
        }
    }

    private function setControllerArguments(\ReflectionMethod $method, $route, array &$arguments): void
    {
        $arguments['_controller'] = $method->getDeclaringClass()->getName() . '::' . $method->getName();
        $routePath = $this->getRoutePathWithoutVariables($route);

        $this->routesPaths[get_bloginfo('url') . $routePath . '/'] = $method;
        $this->routes->add($route->getName(), new Route(
            $route->getPath(),
            $arguments,
            $route->getRequirements(),
            $route->getOptions(),
            $route->getHost(),
            $route->getSchemes(),
            $route->getMethods(),
            $route->getCondition()
        ));
    }

    private function getRoutePathWithoutVariables($route): string
    {
        $path = explode('/{', $route->getPath())[0];
        return (substr($path, 0, 1) !== '/') ? '/' . $path : $path;
    }

    private function checkErrors(): void
    {
        $this->loadWordpressUrls();
        $commonRoutes = array_intersect_key($this->wordpressUrls, $this->routesPaths);
        foreach ($commonRoutes as $key => $error) {
            $this->addError($this->wordpressUrls[$key], $this->routesPaths[$key]);
        }
    }

    private function hasErrors(): bool
    {
        $this->checkErrors();
        return !empty($this->errors);
    }

    private function dispatchErrors(): void
    {
        $this->addWordpressAdminNotice();
        if (is_user_logged_in() && current_user_can('administrator')) {
            $this->addWordpressFrontNotice();
        }
    }

    private function loadWordpressUrls(): void
    {
        $args = ['post_type' => ['post', 'page'], 'posts_per_page' => -1];
        $results = new \WP_Query($args);

        $this->wordpressUrls = [];
        while ($results->have_posts()) {
            $results->the_post();
            global $post;
            $this->wordpressUrls[get_permalink($post->ID)] = sprintf('%s "%s" (%d)', $post->post_type, $post->post_title, $post->ID);
        }
        wp_reset_query();
    }

    private function addWordpressAdminNotice(): void
    {
        add_action('admin_enqueue_scripts', function ($hook): void {
            if ($hook === 'post.php') {
                wp_register_script('routes_validator_admin', get_bloginfo('template_directory') . '/Router/assets/js/adminNotice.js');
                wp_enqueue_script('routes_validator_admin');
                wp_localize_script('routes_validator_admin', 'my_routes_errors', [
                    'error_message' => base64_encode(mb_convert_encoding($this->createErrorMessage(), 'ISO-8859-1', 'UTF-8'))
                ]);
            }
        });
    }

    private function addWordpressFrontNotice(): void
    {
        wp_enqueue_style('notice_alert', get_bloginfo('template_directory') . '/Router/assets/css/notice.css');
        add_action('wp_enqueue_scripts', function (): void {
            wp_register_script('routes_validator_front', get_bloginfo('template_directory') . '/Router/assets/js/frontNotice.js');
            wp_enqueue_script('routes_validator_front');
            wp_localize_script('routes_validator_front', 'my_routes_errors', [
                'error_message' => base64_encode(mb_convert_encoding($this->createErrorMessage(), 'ISO-8859-1', 'UTF-8'))
            ]);
        });
    }


    /**
     * @return array
     */
    public function getRoutesPaths(): array
    {
        return $this->routesPaths;
    }

    /**
     * @return array
     */
    public function getWordpressUrls(): array
    {
        return $this->wordpressUrls;
    }



    private function addError($post, \ReflectionMethod $method): void
    {
        $this->errors[] = sprintf('%s a la même url que la méthode "::%s" de la classe "%s"', $post, $method->getName(), $method->getDeclaringClass()->getName());
    }

    private function createErrorMessage(): ?string
    {
        $errorCount = count($this->errors);
        $message = sprintf('%d conflit%s de route / permalink : ', $errorCount, Tools::plurial($this->errors));

        foreach ($this->errors as $index => $error) {
            $message .= sprintf('<br /> [%d] => %s', $index + 1, $error);
        }

        return $message;
    }

    private function matchResponseReturn(\ReflectionMethod $method): bool
    {
        return $method->getReturnType() && $method->getReturnType()->getName() === 'Symfony\Component\HttpFoundation\Response';
    }

    private function getMethodArguments(\ReflectionMethod $method): array
    {
        $arguments = [];
        foreach ($method->getParameters() as $parameter) {
            $arguments[$parameter->getName()] = $parameter->isOptional() ? $parameter->getDefaultValue() : null;
        }
        return $arguments;
    }

    private function hasValidRoute(\ReflectionMethod $method): bool
    {
        return $this->reader->getMethodAnnotation($method, self::ROUTE_CLASS) !== null;
    }

    private function getRoute(\ReflectionMethod $method): ?SiteRoute
    {
        return $this->reader->getMethodAnnotation($method, self::ROUTE_CLASS);
    }

    private function getReflectionClass($fileName, $namespace): \ReflectionClass
    {
        return new \ReflectionClass($namespace . '\\' . $fileName);
    }
}
