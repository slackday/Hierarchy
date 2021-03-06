<?php
/*
 * This file is part of the Hierarchy package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brain\Hierarchy\Tests\Unit\Branch;

use Brain\Monkey\Functions;
use Brain\Hierarchy\Branch\BranchPage;
use Brain\Hierarchy\Tests\TestCase;
use Mockery;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
final class BranchPageTest extends TestCase
{
    public function testLeavesNoPageNoPagename()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 0;
        $post->post_name = '';
        $post->post_type = '';

        $query = new \WP_Query([], $post, ['pagename' => '']);

        $branch = new BranchPage();

        assertSame(['page'], $branch->leaves($query));
    }

    public function testLeavesNoPage()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 0;
        $post->post_name = '';
        $post->post_type = '';

        $query = new \WP_Query([], $post, ['pagename' => 'foo']);
        Functions::expect('get_page_template_slug')->with($post)->andReturn('');

        $branch = new BranchPage();

        assertSame(['page-foo', 'page'], $branch->leaves($query));
    }

    public function testLeavesPage()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 1;
        $post->post_name = 'foo';
        $post->post_type = 'page';

        $query = new \WP_Query([], $post, ['pagename' => '']);
        Functions::expect('get_page_template_slug')->with($post)->andReturn('');

        $branch = new BranchPage();
        assertSame(['page-foo', 'page-1', 'page'], $branch->leaves($query));
    }

    public function testLeavesPagePagename()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 1;
        $post->post_name = 'foo';
        $post->post_type = 'page';

        $query = new \WP_Query([], $post, ['pagename' => 'bar']);
        Functions::expect('get_page_template_slug')->with($post)->andReturn('');

        $branch = new BranchPage();

        assertSame(['page-bar', 'page-1', 'page'], $branch->leaves($query));
    }

    public function testLeavesPagePagenameTemplate()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 1;
        $post->post_name = 'foo';
        $post->post_type = 'page';

        $query = new \WP_Query([], $post, ['pagename' => 'bar']);
        Functions::expect('get_page_template_slug')->with($post)->andReturn('page-meh.php');
        Functions::expect('validate_file')->with('page-meh.php')->andReturn(0);

        $branch = new BranchPage();

        assertSame(['page-meh', 'page-bar', 'page-1', 'page'], $branch->leaves($query));
    }

    public function testLeavesPagePagenameTemplateFolder()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 1;
        $post->post_name = 'foo';
        $post->post_type = 'page';

        $query = new \WP_Query([], $post, ['pagename' => 'bar']);
        Functions::expect('get_page_template_slug')->with($post)->andReturn('page-templates/page-meh.php');
        Functions::expect('validate_file')->with('page-templates/page-meh.php')->andReturn(0);

        $branch = new BranchPage();

        $expected = ['page-templates/page-meh', 'page-bar', 'page-1', 'page'];

        assertSame($expected, $branch->leaves($query));
    }

    public function testLeavesPagePagenameTemplateNoValidate()
    {
        $post = Mockery::mock('\WP_Post');
        $post->ID = 1;
        $post->post_name = 'foo';
        $post->post_type = 'page';

        $query = new \WP_Query([], $post, ['pagename' => 'bar']);
        Functions::expect('get_page_template_slug')->with($post)->andReturn('page-meh.php');
        Functions::expect('validate_file')->with('page-meh.php')->andReturn(1);

        $branch = new BranchPage();

        assertSame(['page-bar', 'page-1', 'page'], $branch->leaves($query));
    }
}
