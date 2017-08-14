const config = {
    css: [
        `${__dirname}/resources/css`
    ],
    entry: [
        `${__dirname}/resources/js/index`
    ],
    out: {
        dir:  `${__dirname}/public/`,
        name: 'railgun'
    }
};

module.exports = (require('./webpack.base')(config));
