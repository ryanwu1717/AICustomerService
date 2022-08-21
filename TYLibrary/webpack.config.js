"use strict";

require("dotenv").config();

const
    path = require("path"),
    ExtractTextPlugin = require("extract-text-webpack-plugin"),
    ManifestPlugin = require("webpack-manifest-plugin"),
    file_name_pattern = process.env.DEBUG == 1 ? "[name]" : "[name].[chunkhash]",
    extract_sass = new ExtractTextPlugin(`${file_name_pattern}.css`);


module.exports = {
    entry: {
        main: path.join(__dirname, "resources/assets/js/main.js"),
        home: path.join(__dirname, "resources/assets/js/home.js"),
        first: path.join(__dirname, "resources/assets/js/first.js")
        // main:  "resources/assets/js/main.js"
    },
    output: {
        path: path.join(__dirname, "public/build"),
        filename: `${file_name_pattern}.js`
    },

    module: {
        rules: [
            {
                test: /\.scss$/,
                use: extract_sass.extract({
                    use: ["css-loader", "sass-loader"]
                })
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2|jpg|jpeg|png|gif)$/,
                loader: "file-loader?name=./assets/[name].[ext]"
            }
        ]
    },
    plugins: [
        extract_sass,
        new ManifestPlugin()
    ]
};