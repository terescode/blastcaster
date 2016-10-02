/**
 * Grunt wrapper
 */
module.exports = function (grunt) {
  'use strict';

  /**
   * Custom Code
   */
  
  var fs = require('fs'),
    util = require('util'),
    multitasker = require('grunt-multitasker')(grunt),
    xml2js = require('xml2js');

  function phpUnitCompleted(done) {
    return function (error, result, code) {
      if (error) {
        done(error);
      } else {
        if (0 === code) {
          var parser = new xml2js.Parser();
          fs.readFile('./reports/coverage.xml', function (err, data) {
            if (err) {
              done(err);
            } else {
              parser.parseString(data, function (err, result) {
                if (err) {
                  done(err);
                } else {
                  if (result.coverage &&
                      result.coverage.project &&
                      0 < result.coverage.project.length &&
                      result.coverage.project[0].metrics &&
                      0 < result.coverage.project[0].metrics.length &&
                      result.coverage.project[0].metrics[0].$) {
                    var metrics = result.coverage.project[0].metrics[0].$,
                      methods = metrics.methods,
                      coveredMethods = metrics.coveredmethods,
                      statements = metrics.statements,
                      coveredStatements = metrics.coveredstatements,
                      elements = metrics.elements,
                      coveredElements = metrics.coveredelements;
                    if (methods !== coveredMethods &&
                        statements !== coveredStatements &&
                        elements !== coveredElements) {
                      grunt.log.writeln('Code coverage is not within acceptable tolerances.');
                      done(false);
                    } else {
                      done();
                    }
                  } else {
                    grunt.log.writeln('Unexpected coverage data.');
                    grunt.log.writeln(util.inspect(result, false, null));
                    done(false);
                  }
                }
              });
            }
          });
        } else {
          grunt.log.writeln('phpunit returned non-zero result: ' + result + ' (' + code + ')');
          done(false);
        }
      }
    };
  }
  
  /**
   * Grunt task configurations
   */
  grunt.initConfig({
    
    // load the package.json metadata
    pkg: grunt.file.readJSON('package.json'),

    // Clean task configuration
    clean: {
      build: {
        src: [
          'composer-setup.php',
          'build/',
          'reports/'
        ]
      },
      all: {
        src: [
          'composer.phar',
          'vendor/',
          'node_modules/'
        ]
      }
    },
    
    // Composer task
    composer: {
      options: {
        usePhp: true,
        composerLocation: 'composer.phar'
      },
      install: {
        
      },
      update: {
        
      }
    },
    
    // Configure grunt-phpcs (PHP_CodeSniffer) task
    phpcs: {
      application: {
        src: ['*.php', 'includes/*.php', 'admin/*.php', 'public/*.php']
      },
      options: {
        bin: 'vendor/bin/phpcs',
        standard: 'WordPress-Core'
      }
    },

    // Configure grunt-phpunit task
    phpunit: {
      build: {
        options: {
          cmd: 'vendor/bin/phpunit',
          args: [
            '-c',
            'phpunit.xml'
          ]
        }
      }
    },

    // Configure grunt-contrib-jshint task
    jshint: {
      all: ['js/*.js', '!js/*.min.js']
    },

    // Configure grunt-contrib-uglify task
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

    // Configure the WordPress i18n makepot task
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

    // Configure the WordPress i18n addtextdomain task
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
    },
    
    // Configure the grunt-po2mo task for converting .po files to binary .mo files
    po2mo: {
      files: {
        src: 'languages/*.po',
        expand: true
      }
    },
    
    // Configure the compress task
    compress: {
      main: {
        options: {
          archive: 'build/blastcaster.zip'
        },
        src: [
          'admin/**',
          'includes/**',
          'languages/**',
          'blastcaster.php',
          'index.php',
          'readme.txt',
          'uninstall.php'
        ]
      }
    }
  });

  /**
   * Load Grunt plugins and tasks
   */
  grunt.loadNpmTasks('grunt-composer');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-phpcs');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-po2mo');

  /**
   * Custom tasks
   */
  
  function clean(files) {
    files.forEach(function (filepath) {
      grunt.log.writeln('Removing ' + filepath + '...');
      grunt.file.delete(filepath);
    });
  }
  
  /**
   * clean
   * Cleans all the generated files
   */
  grunt.registerMultiTask('clean', 'Clean all generated files', function () {
    clean(this.filesSrc);
  });

  // Set the default clean to be just 'build'
  multitasker.setDefaultTargets('clean', 'build');
  
  /**
   * teardown
   * Tear down the working directory back to a git checkout state.
   */
  grunt.registerMultiTask('teardown', 'Restore checkout state', function () {
    clean(this.filesSrc);
  });
  
  /**
   * phpunit
   * Run phpunit task using the task configuration to spawn a command with
   * specified arguments.
   */
  grunt.registerMultiTask('phpunit', "Runs PHPUnit tests.", function () {
    var options = this.options({});
    grunt.util.spawn({
      cmd: options.cmd,
      args: options.args,
      opts: {
        stdio: 'inherit'
      }
    }, phpUnitCompleted(this.async()));
  });
  
  // Test task - run phpcs and phpunit
  grunt.registerTask('test', ['phpcs', 'phpunit']);
  
  // Build task aliases
  grunt.registerTask('build', ['test', 'compress']);
  
  // Alias the default task to build
  grunt.registerTask('default', ['build']);
};
