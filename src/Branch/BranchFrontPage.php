<?php
/*
 * This file is part of the Hierarchy package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GM\Hierarchy\Branch;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Hierarchy
 */
final class BranchFrontPage implements BranchInterface
{
    /**
     * @inheritdoc
     */
    public function name()
    {
        return 'front_page';
    }

    /**
     * @inheritdoc
     */
    public function is(\WP_Query $query)
    {
        return $query->is_front_page();
    }

    /**
     * @inheritdoc
     */
    public function leaves()
    {
        return ['front-page'];
    }
}