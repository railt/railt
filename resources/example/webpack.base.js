const path    = require("path");
const webpack = require("webpack");

/**
 * PLUGINS
 */
const ExtractTextPlugin       = require("extract-text-webpack-plugin");
const OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");

module.exports = config => {
    return {
        entry:     config.entry,
        output:    {
            path:     config.out.dir,
            filename: `${config.out.name}.js`
        },
        resolve:   {
            modules:    ["node_modules", "resources"],
            extensions: [".js", ".scss", ".flow"]
        },
        externals: [
            require("webpack-require-http")
        ],
        devtool:   "#source-map",
        module:    {
            loaders: [
                {
                    test:    /\.js$/,
                    exclude: /node_modules/,
                    use:     {
                        loader:  "babel-loader",
                        options: {
                            sourceMap: true
                        }
                    },
                },
                {
                    include: /node_modules\/dioma/,
                    test:    /\.js$/,
                    use:     "babel-loader"
                },
                {
                    test: /\.html$/,
                    use:  "html-loader"
                },
                {
                    test: /\.scss$/,
                    use:  ExtractTextPlugin.extract({
                        use: [
                            {
                                loader:  "css-loader",
                                options: {
                                    minimize: true
                                }
                            },
                            {
                                loader:  "postcss-loader",
                                options: {
                                    plugins: () => [
                                        require("autoprefixer")({
                                            browsers: ["last 2 versions"]
                                        })
                                    ]
                                }
                            },
                            {
                                loader:  "sass-loader",
                                options: {
                                    precision:    10,
                                    includePaths: config.css
                                }
                            }
                        ]
                    })
                }
            ]
        },
        plugins:   [
            new ExtractTextPlugin(`${config.out.name}.css`),
            new OptimizeCssAssetsPlugin({
                assetNameRegExp:     /\.optimize\.css$/g,
                cssProcessor:        require("cssnano"),
                cssProcessorOptions: {discardComments: {removeAll: true}},
                canPrint:            true
            })
        ]
    }
};
