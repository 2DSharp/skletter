const webpack = require('webpack');

module.exports = {
    entry: {
        feed: './src/feed.js',
        // pageTwo: './src/pageTwo/index.js',
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
    plugins: [
        new webpack.HotModuleReplacementPlugin()
    ],
    devServer: {
        contentBase: '../../../public/static/js/react-components/',
        hot: true
    }
};