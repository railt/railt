const config = {
    css: [
        `${__dirname}/src`
    ],
    entry: [
        `${__dirname}/src/index`
    ],
    out: {
        dir:  `${__dirname}/out/`,
        name: 'railgun'
    }
};

module.exports = (require('./webpack.base')(config));
