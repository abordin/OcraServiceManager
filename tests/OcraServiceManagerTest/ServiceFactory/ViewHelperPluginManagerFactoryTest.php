<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace OcraServiceManagerTest\ServiceFactory;

use PHPUnit_Framework_TestCase;
use OcraServiceManager\ServiceFactory\ViewHelperPluginManagerFactory;

/**
 * @author  Marco Pivetta <ocramius@gmail.com>
 * @license MIT
 */
class ViewHelperPluginManagerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \OcraServiceManager\ServiceFactory\ViewHelperPluginManagerFactory::createService
     */
    public function testCreateService()
    {

        $factory        = new ViewHelperPluginManagerFactory();
        $eventManager   = $this->getMock('Zend\\EventManager\\EventManagerInterface');

        //'OcraServiceManager\\ServiceManager\\EventManager'

        $serviceLocator = $this->getMock('Zend\\ServiceManager\\ServiceLocatorInterface');
        $serviceLocator->expects($this->any())->method('get')->will($this->returnCallback(
            function ($name) use ($eventManager) {
                if ($name === 'Config') {
                    return array(
                        'ocra_service_manager' => array(
                            'logged_service_manager' => true,
                        ),
                    );
                }

                if ($name === 'OcraServiceManager\\ServiceManager\\EventManager') {
                    return $eventManager;
                }

                throw new \InvalidArgumentException();
            }
        ));

        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('OcraServiceManager\\ServiceManager\\LoggedViewHelperPluginManager', $service);
    }

    /**
     * @covers \OcraServiceManager\ServiceFactory\ViewHelperPluginManagerFactory::createService
     */
    public function testCreateServiceWithoutLogging()
    {

        $factory        = new ViewHelperPluginManagerFactory();
        $eventManager   = $this->getMock('Zend\\EventManager\\EventManagerInterface');

        //'OcraServiceManager\\ServiceManager\\EventManager'

        $serviceLocator = $this->getMock('Zend\\ServiceManager\\ServiceLocatorInterface');
        $serviceLocator->expects($this->any())->method('get')->will($this->returnCallback(
            function ($name) use ($eventManager) {
                if ($name === 'Config') {
                    return array(
                        'ocra_service_manager' => array(
                            'logged_service_manager' => false,
                        ),
                    );
                }

                if ($name === 'OcraServiceManager\\ServiceManager\\EventManager') {
                    return $eventManager;
                }

                throw new \InvalidArgumentException();
            }
        ));

        $service = $factory->createService($serviceLocator);

        $this->assertNotInstanceOf('OcraServiceManager\\ServiceManager\\LoggedViewHelperPluginManager', $service);
        $this->assertInstanceOf('Zend\View\HelperPluginManager', $service);
    }
}
