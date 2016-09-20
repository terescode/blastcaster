module.exports = function (grunt) { //The wrapper function

  // Project configuration & task configuration
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    phpcs: {
      application: {
        src: ['*.php', 'includes/*.php', 'admin/*.php', 'public/*.php']
      },
      options: {
		bin: 'vendor/bin/phpcs',
        standard: 'WordPress-Core'
      }
    },
    
    phpunit: {
	  default: {
        cmd: 'vendor/bin/phpunit',
		args: ['-c', 'phpunit.xml.dist']
	  }
    },

    //The jshint task and its configurations
    jshint: {
      all: ['js/*.js', '!js/*.min.js']
    },

    //The uglify task and its configurations
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        files: [{
          expand: true, // Enable dynamic expansion.
          src: ['js/*.js', '!js/*.min.js'], // Actual pattern(s) to match.
          ext: '.min.js' // Dest filepaths will have this extension.
        }]
      }
    },

    makepot: {
      target: {
        options: {
          domainPath: '/languages', // Where to save the POT file.
          exclude: [], // List of files or directories to ignore.
          include: [], // List of files or directories to include.
          mainFile: 'blastcaster.php', // Main project file.
          potComments: '', // The copyright at the beginning of the POT file.
          potHeaders: {
            poedit: true, // Includes common Poedit headers.
            'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
          }, // Headers to add to the generated POT file.
          type: 'wp-plugin', // Type of project (wp-plugin or wp-theme).
          updateTimestamp: true, // Whether the POT-Creation-Date should be updated without other changes.
          updatePoFiles: true // Whether to update PO files in the same directory as the POT file.
        }
      }
    },

    po2mo: {
      files: {
        src: 'languages/*.po',
        expand: true
      }
    },

    addtextdomain: {
      options: {
        textdomain: 'blastcaster' // Project text domain.
      },
      target: {
        files: {
          src: [
            '*.php',
            '**/*.php',
            '!node_modules/**'
          ]
        }
      }
    }
  });

  //Loading the plug-ins
  grunt.loadNpmTasks('grunt-phpcs');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-po2mo');

  grunt.registerMultiTask('phpunit', "Runs PHPUnit tests.", function() { 
    grunt.util.spawn({ 
      cmd: this.data.cmd, 
      args: this.data.args, 
      opts: {stdio: 'inherit'} 
    }, this.async()); 
  });
	
  // Default task(s), executed when you run 'grunt'
  grunt.registerTask('default', ['build']);

  //Creating a custom task
  grunt.registerTask('test', ['phpcs', 'phpunit']);

};