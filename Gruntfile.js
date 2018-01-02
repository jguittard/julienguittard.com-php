'use strict';

module.exports = function(grunt) {
    // Show elapsed time after tasks run
    require('time-grunt')(grunt);
    // Load all Grunt tasks
    require('jit-grunt')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bowerrc: grunt.file.readJSON('.bowerrc'),
        app: {
            styles: 'public/styles',
            images: 'public/images',
            fonts: 'public/fonts',
            templates: 'src/JG/templates',
            vendor: '<%= bowerrc.directory %>'
        },
        sass: {
            options: {

            },
            local: {
                options: {
                    sourceMap: true
                },
                files: [{
                    expand: true,
                    cwd: '_sass',
                    src: '*.{scss,sass}',
                    dest: '<%= app.styles %>',
                    ext: '.css'
                }]
            },
            prod: {
                options: {
                    outputStyle: 'compressed'
                },
                files: [{
                    expand: true,
                    cwd: '_sass',
                    src: '*.{scss,sass}',
                    dest: '<%= app.styles %>',
                    ext: '.css'
                }]
            }
        },
        wiredep: {
            task: {
                src: [
                    "<%= app.templates %>/layout/default.phtml",
                    "<%= app.templates %>/layout/oartials/head.phtml"
                ],
                ignorePath: '',
                fileTypes: {
                    html: {
                        replace: {
                            js: function (filePath) {
                                return '<script src="/' + filePath.replace('../../', '') + '"></script>';
                            },
                            css: function (filePath) {
                                return '<link rel="stylesheet" href="' + filePath.replace('../../', '/') + '" />';
                            }
                        }
                    }
                }
            }
        },
        copy: {
            local: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= app.vendor %>/sass-bootstrap/fonts',
                    src: ['**/*'],
                    dest: '<%= app.fonts %>'
                }]
            }
        },
        imagemin: {
            options: {
                progressive: true
            },
            prod: {
                files: [{
                    expand: true,
                    cwd: '<%= app.images %>',
                    src: '**/*.{jpg,jpeg,png,gif}',
                    dest: '<%= app.images %>'
                }]
            }
        },
        useminPrepare: {
            html: "<%= app.templates %>/layout/default.phtml"
        },
        usemin: {
            html: "<%= app.templates %>/layout/default.phtml"
        }
    });

    grunt.registerTask('build', [
        'wiredep',
        'sass:local',
        'copy:local',
        'usemin'

    ]);
};