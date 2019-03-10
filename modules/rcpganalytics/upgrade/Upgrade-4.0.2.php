<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a trade license awarded by
 * Garamo Online L.T.D.
 *
 * Any use, reproduction, modification or distribution
 * of this source file without the written consent of
 * Garamo Online L.T.D It Is prohibited.
 *
 *  @author    Reaction Code <info@reactioncode.com>
 *  @copyright 2015-2018 Garamo Online L.T.D
 *  @license   Commercial license
 */

function upgrade_module_4_0_2()
{
    Media::clearCache();

    return true;
}
