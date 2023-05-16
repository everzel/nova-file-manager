<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Tests\Browser;

use Laravel\Dusk\Browser;
use Everzel\NovaFileManager\Tests\DuskTestCase;

/**
 * @group browser
 */
class BrowserTest extends DuskTestCase
{
    /** @test */
    public function can_show_filemanager()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit(route('nova-file-manager.tool', [], false))
                ->assertSee(__('NovaFileManager.title'));
        });
    }
}
