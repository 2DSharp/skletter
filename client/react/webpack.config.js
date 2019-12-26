const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');

module.exports = {

    entry: {
        feed: './src/feed.tsx',
        // pageThree: './src/pageThree/index.js'
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            '@babel/preset-env',
                            '@babel/preset-react', {
                                'plugins': ['@babel/plugin-proposal-class-properties']
                            }],

                    }
                }
            },
            {
                test: /\.css$/i,
                use: ['style-loader', 'css-loader'],
            },
            {
                test: /\.ts(x?)$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: "ts-loader"
                    }
                ]
            },

            // All output '.js' files will have any sourcemaps re-processed by 'source-map-loader'.
            {
                enforce: "pre",
                test: /\.js$/,
                loader: "source-map-loader"
            }
        ],
    },
    /*externals: {
        "react": "react",
        "react-dom": "ReactDOM"
    },*/
    resolve: {
        extensions: ['*', '.js', '.jsx', '.ts', '.tsx']
    },
    output: {
        path: __dirname + '../../../public/static/js/react-components/',
        publicPath: '/static/js/react-components/',
        filename: '[name]-bundle.js'
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/](react|react-dom|axios)[\\/]/,
                    name: 'base',
                    chunks: 'all',
                }
            }
        }
    },
    plugins: [
        new Dotenv({
            path: '../../app/config/.env',
            expand: true
        }),
        new webpack.HotModuleReplacementPlugin()
    ],

    devServer: {
        contentBase: '../../../public/static/js/react-components/',
        hot: true
    }
};