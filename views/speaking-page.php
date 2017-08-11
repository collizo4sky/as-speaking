<?php
/**
 * AlainSchlesser.com Speaking Page Plugin.
 *
 * @package   AlainSchlesser\Speaking
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.alainschlesser.com/
 * @copyright 2017 Alain Schlesser
 */

namespace AlainSchlesser\Speaking;

?><h3>Speaking page view</h3>
<div class="speaking-page-talks">
	<?php foreach ( [ 'Talk A', 'Talk B', 'Talk C' ] as $talk ) : ?>
		<?= $this->renderPartial( 'views/speaking-page-talk', [ 'title' => $talk ] ) ?>
	<?php endforeach; ?>
</div>
