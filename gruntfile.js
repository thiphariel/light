module.exports = function(grunt) {

	require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		uglify: {
			options: {
				mangle: {
					except: ['']
				},
				compress: {
		        	drop_console: false
		      	}
			},
			my_target: {
				files: {
					'web/js/app.min.js': ['web/js/app.js']
				}
			}
		},
		less: {
			development: {
				options: {
					compress: true
				},
				files: {
					"web/css/style.css": "web/less/style.less"
				}
			},
			production: {
				
			}
		},
		phpcs: {
		    application: {
		        dir: ['app', 'src', 'web']
		    },
		    options: {
		        bin: 'vendor/bin/phpcs -n',
		        standard: 'PSR2'
		    }
		},
		watch: {
			scripts: {
				files: ['web/less/*.less'],
				tasks: ['less'],
				options: {
					spawn: false,
					livereload: true,
				},
			},
		},
	});

	grunt.registerTask('default', ['uglify', 'less:development', 'phpcs']);
}