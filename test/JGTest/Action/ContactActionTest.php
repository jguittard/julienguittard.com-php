<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace JGTest\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use JG\Action\ContactAction;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class ContactActionTest
 *
 * @package JGTest\Action
 */
class ContactActionTest extends TestCase
{
    protected $serverRequest;

    protected $delegate;

    protected $renderer;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->serverRequest = $this->prophesize(ServerRequestInterface::class);
        $this->delegate = $this->prophesize(DelegateInterface::class);
        $this->renderer = $this->prophesize(TemplateRendererInterface::class);
    }

    public function testConstructor()
    {
        $middleware = new ContactAction($this->renderer->reveal());
        $this->assertInstanceOf(MiddlewareInterface::class, $middleware);
    }

    public function testProcessGet()
    {
        $this->renderer
            ->render('jg::contact', Argument::type('array'))
            ->willReturn('');
        $homePage = new ContactAction($this->renderer->reveal());
        $response = $homePage->process(
            $this->serverRequest->reveal(),
            $this->delegate->reveal()
        );
        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
