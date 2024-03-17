<?php


class ContainerTest extends TestCase
{

    /**
     *
     * @covers  \engagewp\EngageContainers\Container::get()
     * @covers  \engagewp\EngageContainers\Container::set()
     * @covers  \engagewp\EngageContainers\Container::offsetGet()
     * @covers  \engagewp\EngageContainers\Container::offsetSet()
     */
    public function testSet()
    {
        $container = new \engagewp\EngageContainers\Tests\Mocks\Container();
        $container->set('hi', 'roy' );
        $this->assertEquals( $container[ 'hi'], $container->get('hi' ) );

        $container = new \engagewp\EngageContainers\Tests\Mocks\Container();
        $container[ 'x' ] = 1;
        $this->assertEquals( 1, $container[ 'x' ] );
        $this->assertEquals( $container->get('x'), $container[ 'x' ] );


        $container = new \engagewp\EngageContainers\Tests\Mocks\Container();
        $y = new stdClass();
        $y->x = 1;
        $container->set( 'y', $y );
        $this->assertSame( $y, $container->get( 'y' ) );



    }

    /**
     * @covers  \engagewp\EngageContainers\Container::has()
     * @covers  \engagewp\EngageContainers\Container::offsetExists()
     */
    public function testHas()
    {
        $container = new \engagewp\EngageContainers\Tests\Mocks\Container();
        $container[ 'x' ] = 1;
        $this->assertTrue( $container->has('x' ) );
        $this->assertFalse( $container->has('y' ) );
    }

    /**
     * @covers  \engagewp\EngageContainers\Container::has()
     * @covers  \engagewp\EngageContainers\Container::offsetUnset()
     */
    public function testUnset()
    {
        $container = new \engagewp\EngageContainers\Tests\Mocks\Container();
        $container[ 'x' ] = 1;
        unset( $container['x'] );
        $this->assertFalse( $container->has('x' ) );
    }
}