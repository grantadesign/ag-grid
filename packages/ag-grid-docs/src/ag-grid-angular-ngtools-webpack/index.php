<?php

$pageTitle = "ag-Grid Reference: Angular, ngtools and webpack";
$pageDescription = "ag-Grid is a feature-rich data grid supporting major JavaScript Frameworks. This guide runs through how to build Angular datagrid with ngtools, webpack and TypeScript.";
$pageKeyboards = "Angular Grid ngtools webpack 2";
$pageGroup = "basics";
$angularParent = "checked";

include '../documentation-main/documentation_header.php';
?>

<div>

    <h1 id="angular-building-with-webpack">Angular - Building with @ngtools/webpack</h1>

    <p>We document the main steps required when using ag-Grid, Angular, Typescript and @ngtools/Webpack below, but please refer to
        <a href="https://github.com/ag-grid/ag-grid-angular-example">ag-grid-angular-example</a> on GitHub for a full working example of this.</p>

        <h3>Initialise Project</h3>

        <snippet>
mkdir ag-grid-ngtools
cd ag-grid-ngtools
npm init
// accept defaults</snippet>

        <h3>Install Dependencies</h3>

        <snippet>
npm i --save ag-grid ag-grid-angular
npm i --save @angular/common @angular/compiler @angular/compiler-cli @angular/core @angular/platform-browser @angular/platform-browser-dynamic @angular/router typescript rxjs core-js zone.js
npm i --save-dev webpack webpack-dev-server @ngtools/webpack angular2-template-loader awesome-typescript-loader extract-text-webpack-plugin file-loader canonical-path @types/node
npm i --save-dev css-loader style-loader html-loader html-webpack-plugin raw-loader url-loader

// optional - only necessary if you're using any of the Enterprise features
npm i --save ag-grid-enterprise</snippet>

        <h3>Create Application</h3>

        <p>Our application will be a very simple one, consisting of a single Module, a single Component and a bootstrap file, as well a few utility files for vendor & polyfills.</p>

    <p>The resulting project structure will look like this:</p>
    <snippet>
ag-grid-ngtools
├── app
│   ├── app.component.html
│   ├── app.component.ts
│   ├── app.module.ts
│   ├── boot-aot.ts
│   ├── boot.ts
│   ├── polyfills.ts
│   ├── vendor-aot.ts
│   └── vendor.ts
├── config
│   ├── helpers.js
│   ├── index.html
│   ├── webpack.dev.js
│   └── webpack.prod.js
├── dist
├── node_modules
├── package.json
├── tsconfig.aot.json
└── tsconfig.json</snippet>

<snippet>
// app/app.module.ts 
import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
// ag-grid
import {AgGridModule} from "ag-grid-angular/main";
// application
import {AppComponent} from "./app.component";

@NgModule({
    imports: [
        BrowserModule,
        AgGridModule.withComponents([]
        )
    ],
    declarations: [
        AppComponent
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}</snippet><snippet>
// app/app.component.ts 
import {Component} from "@angular/core";

import {GridOptions} from "ag-grid/main";

@Component({
    selector: 'my-app',
    templateUrl: 'app.component.html'
})
export class AppComponent {
    public gridOptions:GridOptions;
    public rowData:any[];
    public columnDefs:any[];

    constructor() {
        // we pass an empty gridOptions in, so we can grab the api out
        this.gridOptions = &lt;GridOptions&gt;{
            onGridReady: () =&gt; {
                this.gridOptions.api.sizeColumnsToFit();
            }
        };
        this.columnDefs = [
            {headerName: "Make", field: "make"},
            {headerName: "Model", field: "model"},
            {headerName: "Price", field: "price"}
        ];
        this.rowData = [
            {make: "Toyota", model: "Celica", price: 35000},
            {make: "Ford", model: "Mondeo", price: 32000},
            {make: "Porsche", model: "Boxter", price: 72000}
        ];
    }
}</snippet><snippet>
// app/app.component.html 
&lt;ag-grid-angular #agGrid style="width: 500px; height: 150px;" class="ag-theme-balham"
                 [gridOptions]="gridOptions"
                 [columnDefs]="columnDefs"
                 [rowData]="rowData"&gt;
&lt;/ag-grid-angular&gt;</snippet><snippet>
// app/boot.ts 
import {platformBrowserDynamic} from "@angular/platform-browser-dynamic";
import {AppModule} from "./app.module";

// for enterprise customers
// import {LicenseManager} from "ag-grid-enterprise/main";
// LicenseManager.setLicenseKey("your license key");

platformBrowserDynamic().bootstrapModule(AppModule);</snippet><snippet>
// app/polyfills.ts 
import "core-js/es6";
import "core-js/es7/reflect";
require('zone.js/dist/zone');
if (process.env.ENV === 'production') {
    // Production
} else {
    // Development
    Error['stackTraceLimit'] = Infinity;
    require('zone.js/dist/long-stack-trace-zone');
}</snippet>

        <h2>Configuration</h2>

        <p>We have 2 Webpack Configurations in the example project - a dev configuration and a production configuration
            (with AOT support). In both of these configurations we make use of an html file where our generated bundle(s)
            will be inserted and will serve as our application starting point, as well as a helper file for within use of
            the webpack configurations:</p>
        <snippet>
// config/helpers.js
var path = require('path');
var _root = path.resolve(__dirname, '..');
function root(args) {
    args = Array.prototype.slice.call(arguments, 0);
    return path.join.apply(path, [_root].concat(args));
}
exports.root = root;</snippet><snippet>
&lt;!-- config/index.html --&gt;
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;base href="/"&gt;
    &lt;title&gt;ag-Grid & Angular With Webpack&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
&lt;my-app&gt;Loading...&lt;/my-app&gt;
&lt;/body&gt;
&lt;/html&gt;</snippet>

        <p><code>helpers.js</code> helps us to resolve path easily, and <code>index.html</code> will be used by the
            <code>HtmlWebpackPlugin</code> plugin to ensure the generated bundles are inserted dynamically, instead of us
            needing to manage this ourselves.</p>

        <h3 id="webpack-dev-configuration">Development Configuration</h3>

    <h4 id="dev-tsconfig">tsconfig Configuration</h4>

    <p>For development purposes we have a simple configuration:</p>
    <snippet>
// tsconfig.json
{
  "compilerOptions": {
    "target": "es5",
    "module": "commonjs",
    "moduleResolution": "node",
    "sourceMap": true,
    "emitDecoratorMetadata": true,
    "experimentalDecorators": true,
    "removeComments": false,
    "noImplicitAny": false,
    "lib": ["dom","es2015"]
  },
  "compileOnSave": true,
  "exclude": [
    "node_modules/*",
    "app/boot-aot.ts"
  ]
}</snippet>

    <p>Note that here we exclude the AOT bootstrap file as the AOT bootstrap file will have references to Angular Factories that
        won't exist yet.</p>

    <h4 id="dev-vendor">Vendor Entry File</h4>

    <p>Here we add any vendor (or third-party) related libraries - note that we've included the ag-Grid CSS and
        chosen theme ("Fresh" in this case), as well as included the <code>ag-grid-enterprise</code> dependency.</p>

    <p>The <code>ag-grid-enterprise</code> inclusion is only necessary it you're using Enterprise features - it can be ommitted if not.</p>

    <p>Note too that we've included <code>@angular/platform-browser-dynamic</code> - this is necessary for JIT (Just-In-Time)/Development mode,
        but can be dropped for production/AOT builds (see later for more on this).</p>

    <snippet>
// app/vendor.ts 
// Angular
import '@angular/platform-browser';
import '@angular/platform-browser-dynamic';
import '@angular/core';
import '@angular/common';

// RxJS
import 'rxjs';

// ag-grid
import 'ag-grid/dist/styles/ag-grid.css';
import 'ag-grid/dist/styles/ag-theme-balham.css';
import 'ag-grid-angular/main'

// for ag-grid-enterprise users only 
//import 'ag-grid-enterprise/main';</snippet>

    <h4>Webpack Development Configuration</h4>

    <snippet>
// config/webpack.dev.js
var webpack = require('webpack');
var helpers = require('./helpers');
var path = require('path');

var HtmlWebpackPlugin = require('html-webpack-plugin');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    devtool: 'cheap-module-eval-source-map',

    entry: {
        'polyfills': './app/polyfills.ts',
        'vendor': './app/vendor.ts',
        'app': './app/boot.ts'
    },

    output: {
        path: helpers.root('dist'),
        publicPath: 'http://localhost:8080/',
        filename: '[name].js',
        chunkFilename: '[id].chunk.js'
    },

    resolve: {
        extensions: ['.ts', '.js']
    },

    module: {
        loaders: [
            {
                test: /\.ts$/,
                exclude: path.resolve(__dirname, "node_modules"),
                loaders: ['awesome-typescript-loader', 'angular2-template-loader']
            },
            {
                test: /\.html$/,
                loader: 'html-loader',
                query: {
                    minimize: false // workaround for ng2
                }
            },
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                loader: 'file-loader?name=[path]/[name].[ext]'
            },
            {
                // site wide css (excluding all css under the app dir)
                test: /\.css$/,
                exclude: helpers.root('app'),
                loader: ExtractTextPlugin.extract({fallback: 'style-loader', use: 'css-loader?sourceMap'})
            },
            {
                // included styles under the app directory - these are for styles included
                // with styleUrls
                test: /\.css$/,
                include: helpers.root('app'),
                loader: 'raw-loader'
            }
        ]
    },

    plugins: [
        new webpack.optimize.CommonsChunkPlugin({
            name: ['app', 'vendor', 'polyfills']
        }),

        new ExtractTextPlugin({filename: '[name].css'}),

        new HtmlWebpackPlugin({
            template: 'config/index.html'
        })
    ],

    devServer: {
        historyApiFallback: true,
        stats: 'minimal'
    },
};</snippet>

        <p>
            <code>entry</code>
        </p>
        <p>We could generate one large bundle, but it's better to break the bundle up into the fairly "static" dependencies
            and the more fluid application code. Using the <code>entry</code> property we can specify the entry points we
            want to use - we have specified 3 here:
        </p>
        <ul class="content">
            <li>polyfills: polyfills we require to run Angular / ES6 applications in current browsers.</li>
            <li>vendor: the vendor (or 3rd party) libraries we need - ag-Grid, Angular etc.</li>
            <li>app: our application code.</li>
        </ul>

        <p>
            <code>resolve</code>
        </p>
        <p>As our imports done specify what file extension to use, we need to specify what file types we want to match on - in this case
            we're looking at TypeScript and JavaScript files, but you could also add CSS & HTML files too.</p>

        <p>
            <code>module.loaders</code>
        </p>
        <p>Loaders tell Webpack how & what to do with certain types of file - we have specified a few here to deal with Typescript, HTML, CSS and Images:</p>
        <ul class="content">
            <li>awesome-typescript-loader: transpile Typescript to ES5</li>
            <li>angular2-template-loader: processes Angular components' template/styles</li>
            <li>html</li>
            <li>images & fonts</li>
            <li>css: the first phe pattern matches application-wide styles, and the
                second handles component-scoped styles (ie with styleUrls)</li>
        </ul>

        <p>
            <code>plugins</code>
        </p>
        <ul class="content">
            <li>CommonsChunkPlugin: separates our entry points into distinct files (one each for polyfills, vendor and application)</li>
            <li>HtmlWebpackPlugin: takes our supplied template index.html and inserts the generates JS & CSS files for us</li>
        </ul>

        <p>The dev configuration doesn't generate any files - it keeps all bundles in memory, so you won't find any artifacts in the dist directory (from this configuration).</p>

        <h3 id="webpack-production-configuration">Production Configuration</h3>
    <h4 id="prod-tsconfig">tsconfig Configuration</h4>

    <snippet>
// tsconfig.aot.json
{
  "compilerOptions": {
    "target": "es5",
    "module": "commonjs",
    "moduleResolution": "node",
    "sourceMap": true,
    "emitDecoratorMetadata": true,
    "experimentalDecorators": true,
    "removeComments": false,
    "noImplicitAny": false,
    "lib": ["dom","es2015"]
  },
  "compileOnSave": true,
  "exclude": [
    "node_modules/*",
    "aot/",
    "app/boot-aot.ts"
  ],
  "angularCompilerOptions": {
    "genDir": "aot/",
    "skipMetadataEmit": true
  },
  "atom": {
    "rewriteTsconfig": false
  }
}
   </snippet>

    <p>We exclude the aot output directory and the AOT bootstrap file (again, as the AOT bootstrap file will have references to Angular Factories that
        won't exist yet).</p>

    <h4 id="prod-vendor">Vendor AOT Entry File</h4>

    <p>Here we add any vendor (or third-party) related libraries - note that we've included the ag-Grid CSS and
        chosen theme ("Fresh" in this case), as well as included the <code>ag-grid-enterprise</code> dependency.</p>

    <p>The <code>ag-grid-enterprise</code> inclusion is only necessary it you're using Enterprise features - it can be ommitted if not.</p>

    <p>This time we've dropped <code>@angular/platform-browser-dynamic</code> as we won't be compiling anything at
        runtime in the browser.</p>

    <snippet>
// app/vendor-aot.ts 
// Angular
import '@angular/platform-browser';
import '@angular/core';
import '@angular/common';
import '@angular/router';

// RxJS
import 'rxjs';

// ag-grid
import 'ag-grid/dist/styles/ag-grid.css';
import 'ag-grid/dist/styles/ag-theme-balham.css';
import 'ag-grid-angular/main'

// for ag-grid-enterprise users only 
//import 'ag-grid-enterprise/main';</snippet>

    <p>As we'll be pre-compiling our application, we can use the generated factory to bootstrap our application, which will result
    in much faster load times:</p>

<snippet>
// app/boot-aot.ts 
import {platformBrowser} from "@angular/platform-browser";
import {AppModuleNgFactory} from "../aot/app/app.module.ngfactory";

// for enterprise customers
// import {LicenseManager} from "ag-grid-enterprise/main";
// LicenseManager.setLicenseKey("your license key");

platformBrowser().bootstrapModuleFactory(AppModuleNgFactory);</snippet>

    <h3 id="production-configuration">Webpack AOT/Production Configuration</h3>
    <snippet>
// config/webpack.prod.js
const path = require('path');
const webpack = require('webpack');

const helpers = require('./helpers');

const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const AotPlugin = require('@ngtools/webpack').AotPlugin;

const ENV = process.env.NODE_ENV = process.env.ENV = 'production';

module.exports = {
    devtool: 'source-map',

    entry: {
        polyfills: './app/polyfills.ts',
        vendor: './app/vendor-aot.ts',
        app: './app/boot-aot.ts'
    },

    output: {
        path: helpers.root('dist/aot'),
        publicPath: '/',
        filename: '[name].[hash].js',
        chunkFilename: '[id].[hash].chunk.js'
    },

    resolve: {
        extensions: ['.js', '.ts']
    },

    module: {
        loaders: [
            {
                test: /\.ts$/,
                loader: '@ngtools/webpack'
            },
            {
                test: /\.html$/,
                loader: 'html-loader'
            },
            {
                test: /\.(png|jpe?g|gif|svg|woff|woff2|ttf|eot|ico)$/,
                loader: 'file-loader?name=[path]/[name].[ext]'
            },
            {
                // site wide css (excluding all css under the app dir)
                test: /\.css$/,
                exclude: helpers.root('app'),
                loader: ExtractTextPlugin.extract({fallback: 'style-loader', use: 'css-loader?sourceMap'})
            },
            {
                // included styles under the app directory - these are for styles included
                // with styleUrls
                test: /\.css$/,
                include: helpers.root('app'),
                loader: 'raw-loader'
            }
        ]
    },
    plugins: [
        new webpack.optimize.CommonsChunkPlugin({
            name: ['app', 'vendor', 'polyfills']
        }),

        new AotPlugin({
            tsConfigPath: './tsconfig.aot.json',
            entryModule: helpers.root('app/app.module#AppModule')
        }),

        new HtmlWebpackPlugin({
            template: 'config/index.html'
        }),

        new webpack.optimize.UglifyJsPlugin({
            beautify: false,
            comments: false,
            compress: {
                screw_ie8: true,
                warnings: false
            },
            mangle: {
                keep_fnames: true,
                screw_i8: true
            }
        }),

        new ExtractTextPlugin({filename: '[name].[hash].css'}),

        new webpack.DefinePlugin({
            'process.env': {
                'ENV': JSON.stringify(ENV)
            }
        })
    ]
};</snippet>
    <p>We don't use a development server with this configuration - we generate the final artifacts in the dist/ folder and expect this to be deploy to a server.</p>
    <p>We use the <code>@ngtools/webpack</code> plugin to transpile our Angular code, including the AOT step</p>
    <p>Finally, we use the DefinePlugin to provide an environment variable that we can use in our application code to <code>enableProdMode()</code></p>
    <snippet>
if (process.env.ENV === 'production') {
    enableProdMode();
}</snippet>

        <p>With all this in place, we can now add the following npm scripts to our package.json:</p>

        <snippet>
  "scripts": {
    "start": "webpack-dev-server --config config/webpack.dev.js --inline --progress --port 8080",
    "build": "webpack --config config/webpack.prod.js --progress --profile --bail"
  },
   </snippet>

        <p>Now we can either run <code>npm start</code> to run the development setup, or <code>npm run build</code> for the production build.
            In the case of the production build the generated files will be under the <code>dist/</code> folder.</p>

        <p>If we now run our applicatiom with the above code we will see this:</p>

        <img src="../images/webpack_app.png" style="width: 100%">

        <h3>Override ag-Grid CSS</h3>
        <p>There are many ways to override the CSS with Webpack, but if you use the configuration above then you can override ag-Grid CSS as follows:</p>
        <ul class="content">
            <li>Place your application-wide CSS file(s) in a directory other than <code>./app</code> - for example <code>./css/</code>.
                Remember that CSS under <code>./app</code> is treated differently - it is used for component-scoped styles.</li>
            <li>In a suitable component - we suggest <code>boot.ts</code> import the CSS you want to include:</li>
            <snippet>
import '../css/app.css';</snippet>
        </ul>

        <p>And that's it - you can now override ag-Grid CSS with your own in <code>./css/app.css</code>. For example, the following
            would set the cell background to green across the board.</p>
        <snippet>
.ag-cell {
    background-color: green;
}</snippet>
    </div>

<?php include '../documentation-main/documentation_footer.php'; ?>
