<?php
require_once("templates/header.php");
?>

<div class="container">
	<div class="row">
		<div class="span3 bs-docs-sidebar" id="pagenav">
			<ul class="nav nav-list bs-docs-sidenav" data-spy="affix">
				<li class="active"><a href="#intro"><i class="icon-chevron-right"></i> Introduction</a></li>
				<li><a href="#modules"><i class="icon-chevron-right"></i> Module Types</a></li>
				<li><a href="#code"><i class="icon-chevron-right"></i> Code Architecture</a></li>
				<li><a href="#i18n"><i class="icon-chevron-right"></i> Translations / I18N</a></li>
				<li><a href="#sasssss"><i class="icon-chevron-right"></i> SASS</a></li>
				<li><a href="#generatingDoc"><i class="icon-chevron-right"></i> JSDoc and PHPDoc</a></li>
			</ul>
		</div>
		<div class="span9">

			<section id="intro">
				<div class="hero-unit">
					<h1>Developer Doc</h1>
					<p>
						This document explains more than you ever wanted to know about the Data Generator:
						how it works, how it's structured, and how to extend it. May the thrills commence.
					</p>
				</div>

				<h2>Introduction</h2>
				<p>
					If you haven't already done so check out the <a href="http://www.generatedata.com" target="_blank">script
					online</a> and generate some data. You should have a general sense about what the script does
					before you bother reading any further.
				</p>
				<p>
					Version 3.0.0 of the Data Generator was a complete redesign of the script to make it properly <i>modular</i>:
					now it's primarily an <i>engine</i> that includes an interface, installation script, user account
					system, and a standardized way for plugins to be integrated with the script. The really interesting part
					is the plugins themselves: they provide all the functionality of the script. And that's where you come in.
				</p>
				<p>
					The Developer Doc focuses on how you, as a developer, can write new modules. Different module types require 
					degrees of technical knowledge: providing new translations is very basic; providing new country plugins is 
					fairly simple, but requires basic PHP; creating new Export and Data Types are complicated and will require
					both JS and PHP expertise. But I'm getting ahead of myself... let's start with an overview to the 
					module types.
				</p>
			</section>

			<section id="modules">
				<h2>Module Types</h2>

				<p class="alert alert-info">
					Note: the words <b>plugin</b> and <b>module</b> are used synonymously.
				</p>
				<p>
					The Data Generator accommodates the following types of module:
				</p>

				<table class="table table-bordered table-striped">
				<tr>
					<td width="120"><a href="dataTypes.php">Data Types</a></td>
					<td>
						<p>
							These govern what kind of data can be generated through the interface. You get a huge amount of control and
							customizability out of these suckers. For example:
						</p>
						<ul>
							<li>They can generate anything you want - strings, numbers, URLs, images, binary data, code, ascii art, you
							name it.</li>
							<li>They can display any arbitrary settings to allow in-row configuration by the user, customizing the
							particular output for the Data Type row.</li>
							<li>You can add custom JS validation to ensure the values are well formed.</li>
							<li>They can access and depend on other Data Types in the generated result sets to customize their output.</li>
							<li>They can generate different content depending on the selected Export Type (HTML, CSV, XML, etc.)
							and the export target (in-page, prompt to download, new tab).
						</ul>
						<a href="dataTypes.php" class="btn btn-primary btn-small">More about Data Types &raquo;</a>
					</td>
				</tr>
				<tr>
					<td><a href="exportTypes.php">Export Types</a></td>
					<td>
						<p>
							Export Types are the formats in which the data is actually generated: XML, HTML, CSV, JSON, etc.
						</p>

						<a href="exportTypes.php" class="btn btn-primary btn-small">More about Export Types &raquo;</a>
					</td>
				</tr>
				<tr>
					<td><a href="countryData.php">Country Plugins</a></td>
					<td>
						<p>
							In order to generate realistic-looking human-related data, you need to actually provide the
							data set to pull from. The Country plugins let you do just this: you provide some data
							country, regions and cities for a particular country. This allows various Data Types to intelligently
							generate rows of data with regions, cities and postal codes that match the country selected. These are
							very simple plugins to create.
						</p>
						<a href="countryData" class="btn btn-primary btn-small">More about Country Data &raquo;</a>
					</td>
				</tr>
				<tr>
					<td><a href="translations.php">Translations</a></td>
					<td>
						<p>The entire Data Generator interface is translatable. At the top right of the interface, there's a dropdown
						that lists all available languages. The default languages other than English were auto-generated with
						Google Translate. As such, they're in need of proper translations! Click the button below to learn more
						about translations and how to provide your own / update the existing ones.</p>
						<a href="translations.php" class="btn btn-primary btn-small">More about Translations &raquo;</a>
					</td>
				</tr>
				</table>
			</section>

			<section id="code">
				<h2>Code Architecture</h2>

				<p>
					A few words on how the code is organized, to give you a sense of how it all fits together.
				</p>

				<h3>PHP</h3>

				<p>
					The <code>settings.php</code> found in the root folder contains the unique settings for the current
					installation - MySQL database settings,  and so on. This file is automatically created by 
					the installation script. <i>This is the only file that contains custom information for 
					the installation</i>.
				</p>

				<p>
					The PHP codebase is <b>object-oriented</b>, with all core classes found in <code>/resources/classes/</code>. 
					The <code>library.php</code> file - again found in the root - is used as the main entry point: all code
					that needs access to the core codebase just needs to include that single file. 
				</p>

				<h4>Core.class.php</h4>

				<p>
					The <code>Core.class.php</code> file is special. It's a static class (or would be if PHP permitted it!) 
					that acts as the global namespace for the backend code. When <code>Core::init()</code> is run, it does 
					all the stuff you need to run the script, namely:
				</p>

				<ul>
					<li>Parses the <code>settings.php</code> file and stores all the custom settings for the environment.</li>
					<li>Makes a connection to the database.</li>
					<li>Automatically handles serious errors like database connection problems, or Smarty not being able to generate
						the page due to permission errors.</li>
					<li>Loads up all Data Types, Export Types and Country plugins and renders them appropriately on the screen.</li>
					<li>Loads the current language file.</li>
					<li>Lots of other nagging juicy stuff.</li>
				</ul>

				<p>
					It also contains numerous helper functions. Check out the source code for more details.
				</p>

				<h4>Smarty Templates</h4>
				<p>
					<i>So... where the hell's the markup?</i> If you're anything like me, you hate examining a new codebase to find 
					you can't even track down the HTML. I know, it's annoying. Check out <code>/resources/templates/</code>. That contains the bulk of the HTML used to generate 
					webpages. You can read more about Smarty <a href="http://smarty.net" target="_blank">on their website</a>. The script 
					uses version 3.
				</p>

				<h4>Custom Smarty Functions</h4>
				<p>
					When you look through the templates, you may notice the occasional non-standard Smarty function, like 
					<code>{country_plugins}</code>. These are all found in <code>/resources/libs/smarty/plugins/</code>. That's actually
					the same folder as all the default Smarty modules and functions. If you're not familiar with Smarty, it's worth
					fishing through that folder to get an idea of how those files map to actual functions and modifies that you can
					use in the Smarty templates.
				</p>

				<h3>JavaScript</h3>

				<p>
					The client-side code is built around <a href="http://requirejs.org/" target="_blank">requireJS</a>. All the 
					JS module code works the same way, regardless of whether the code is the Core, for a Data Type or Export 
					Type. Country plugins are entirely PHP - no JS required.
				</p>

				<ul>
					<li>Each module is sandboxed by RequireJS, to ensure it doesn't pollute the global namespace.</li>
					<li>Modules interact with one another using <b>publish / subscribe messages</b>, not by calling one another 
						directly.</li>
					<li>All modules register themselves with the <b>Manager</b>, which is found here: <code>/resources/scripts/manager.js</code>. 
						The Manager handles all pub/sub messaging and ensuring that the module being registered contains all the 
						required functions in order to integrate with the script.</li>
					<li>All save/load functionality for a Data Type and Export Type is done via the JS module. When the user saves
						or loads a data set via the interface, the core script calls all appropriate module's JS module files that 
						serialize the data for database storage, or are passed the information to re-populate the page data. It's 
						actually pretty simple once you see it in action: see the <a href="dataTypes.php">Data Types</a> or 
						<a href="exportTypes.php">Export Types</a> pages for more information.</li>
				</ul>

				<p>
					The pub/sub messages can be viewed right in the Data Generator by going to the <code>Settings</code> tab and 
					choosing which information you want to see in your browser console through the <code>Developer</code> section.
				</p>

				<p>
					See the appropriate module documentation section for more info on how all this works from a practical
					viewpoint.
				</p>
			</section>

			<section id="i18n">
				<h2>Translations / I18N</h2>

				<p>
					The Data Generator has built-in multi-language support; a user can easily change the UI language via a dropdown
					and the page automatically redraws with the new language. This means all language strings for the Core and all
					plugins need to be extracted and placed 
				</p>

				<p>
					After a lot of humming and hawing, I decided to use a simple PHP array to store the language strings.
					The Core language strings are found in <code>/resources/lang/</code>. There you'll see there's a separate file 
					for each language. The Data Types and Export Types all have their own language files which need to be stored in a 
					<code>/plugins/[data-or-export-type-folder]/lang/</code> folder. When the user picks a language through the interface,
					the Core script automatically figures out what language files are present and attempts to load the right one. If it 
					can't find it, it will load the default English one (yes, an <code>en.php</code> language file is required for 
					Data and Export Type modules).
				</p>

				<h3>Google Translate auto-translations</h3>

				<p>
					The base translation is provided by Google Translate. It's pretty poor, but it's better than nothing. 
					I'd LOVE people to help improve the translations! For more info on that, see the 
					<a href="translations.php">Translations</a> page.
				</p>
			</section>

			<section id="sasssss">
				<h2>SASS</h2>
				<p>
					In the unlikely event of you needing to tweak the CSS for the Core script, bear in mind it's auto-generated
					based on SASS templates so updating the CSS is the wrong way to go about it. You'll need to edit the 
					SASS files at <code>/resources/themes/[theme]/sass/</code>. 
				</p>
				<p>
					Check out <a href="http://sass-lang.com/">sass-lang.com</a> for more information.
				</p>
			</section>

			<section id="generatingDoc">
				<h2>JSDoc and PHPDoc</h2>
				<p>
					If you click on "Developer Doc" at the top left of this page, you'll notice two additional dropdown items appear: 
					<code>PHP Documentation</code> and <code>JS Documentation</code>. Those are auto-generated from the codebase using 
					PHPDocumentor and JSDoc. We've bundled both of those scripts with the Data Generator so that it's really easy to 
					create and include your own module documentation.
				</p>

				<p class="alert alert-info">
					<b>Note</b>: when it comes to submitting pull-requests via github, we'd rather you didn't include the documentation. 
					We'll auto-generate it on our end.
				</p>

				<h4>Updating the JSDoc</h4>
				<p>
					JSDoc requires you to have the Java runtime installed. On the command line, go to your
					<code>[generatedata root]/libs/jsdoc-toolkit/</code> folder and enter the following command:
				</p>

				<pre>java -jar jsrun.jar app/run.js -a -D="noGlobal:true" -t=templates/bootstrap -d=../../docs/jsdoc ../../scripts/generator.js ../../scripts/utils.js ../../scripts/manager.js -r=2 ../../plugins/*</pre>

				<h4>Updating the PHPDoc</h4>

				<pre>php phpdoc.php --template=<b>[generatedata root path]</b>/docs/gdPHPDocTemplate -d <b>[generatedata root path]</b>/classes/ -d <b>[generatedata root path]</b>/plugins/countries/ -d <b>[generatedata root path]</b>/plugins/dataTypes/ -d <b>[generatedata root path]</b>/plugins/exportTypes/ -t <b>[generatedata root path]</b>/docs/phpdoc --sourcecode</pre>
			</section>

		</div>
	</div>
</div>

<?php
require_once("templates/footer.php");
?>
