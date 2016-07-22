<?php namespace Love4work\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('cart', 'Love4work\Cart\Cart');

		$this->publishes([
			__DIR__.'/../config/cart.php' => config_path('cart.php')
		], 'config');
		
		if ( ! class_exists('CreateShoppingcartTable')) {
			// Publish the migration
			$timestamp = date('Y_m_d_His', time());
			$this->publishes([
				__DIR__.'/../database/migrations/0000_00_00_000000_create_shoppingcart_table.php' => database_path('migrations/'.$timestamp.'_create_shoppingcart_table.php'),
			], 'migrations');
		}

	}

}
