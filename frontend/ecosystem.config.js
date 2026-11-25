module.exports = {
    /**
     * Application configuration section
     * http://pm2.keymetrics.io/docs/usage/application-declaration/
     */
    apps: [

        // First application
        {
            name: 'Armatrip',
            script: "./server/index.js",
            "env": {
                "PM2_SERVE_PORT": 7080,
		"NODE_PORT":7080
            }
        },
    ],
};
