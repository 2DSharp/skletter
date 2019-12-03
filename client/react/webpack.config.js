const webpack = require('webpack');
const Dotenv = require('dotenv-webpack');

module.exports = {

    entry: {
        feed: './src/feed.js',
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
            }
        ],
    },
    resolve: {
        extensions: ['*', '.js', '.jsx']
    },
    output: {
        path: __dirname + '../../../public/static/js/react-components/',
        publicPath: '/static/js/react-components/',
        filename: '[name]-bundle.js'
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
        },
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