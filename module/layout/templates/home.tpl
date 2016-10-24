{php}
	loadModule("header");
{/php}
<section id="content">
	{php}
		loadModule("slideshow");
	{/php}
	{php}
		loadModule("product","home");
		loadModule("product","category_product");
		loadModule("tintuc","tinmoi");
	{/php}
</section>
{php}
	loadModule("footer");
{/php}
