/*
Usage
--------------
# run build tasks is build_state (default dev)
# start watching for changes and build on changes
grunt


# build with compressed css and js
# does NOT start watch
grunt prod

Project Setup
--------------
# install grunt for this project (generates ./node_modules)
# run from within this directory
npm install

# NOTE: Machine Setup (heading below) must have occurred
# at least once previously on this machine


Machine Setup
--------------
# install nodejs from
http://nodejs.org/download/

# from the command line install grunt cli
sudo npm install -g grunt-cli

# from the commdand line install sass
sudo gem install sass
*/

    // we can set our default build state to either
    // 'prod' or 'dev'
    // this value will be used for both the
    // initial process when `grunt` alone is run
    // as well as the tasks run via watch
var build_state = 'prod',
    // sass src files are loaded with sass includes
    // no need to list them here (i.e. only one sass src)
    sass_files = {
        "custom_style.css": "sass/custom_style.css.scss",
    },
    // watch all .scss files in our sass directory
    // for changes
    watched_sass_files = [
        'sass/*.scss'
    ];

    // haml_files = [{
    //     expand: true,
    //     cwd: 'haml/',
    //     src: '**/*.haml',
    //     dest: 'www/',
    //     ext: '.html'
    // }],

    // watched_haml_files = ['haml/**/*.haml'];

module.exports = function(grunt) {
    "use strict";

    grunt.initConfig({
        // `grunt watch`
        watch: {
            sass: {
                files: watched_sass_files,
                tasks: ["sass:"+build_state],
                options: {
                    livereload: true,
                    style: "normal"
                }
            }
            // haml: {
            //     files: watched_haml_files,
            //     tasks: ["haml:"+build_state],
            //     options: {
            //         livereload: true
            //     }
            // }
        }, // watch

        sass: {
            // `grunt sass:dev`
            dev: {
                options: { style: "normal", sourcemap: true },
                files: sass_files,
            },
            // `grunt sass:prod`
            prod: {
                options: { style: "compressed", sourcemap: true },
                files: sass_files,
            }

        } // sass
        // haml: {
        //     // 'grunt haml:dev'
        //     dev: {
        //         options: { style: "expanded" }, 
        //         files: haml_files,
        //     },
        //     // 'grunt haml:prod'
        //     prod: {
        //         options: { style: "expanded" },
        //         files: haml_files,
        //     }
        // }
    });

    // when `grunt` is run, do the following tasks
    // run all tasks associated with build_state
    // (either prod or dev), start watch
    // (note: watch also uses build_state when generating output)
    grunt.registerTask('default', [build_state, 'watch']);

    // when `grunt prod` is run, do the following tasks
    grunt.registerTask('prod', ['sass:prod']);

    // load these tasks (necessary to allow use of sass, watch, and uglify
    grunt.loadNpmTasks("grunt-contrib-sass");
    grunt.loadNpmTasks("grunt-contrib-watch");
};

