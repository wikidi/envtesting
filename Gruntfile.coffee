module.exports = (grunt) ->

  grunt.initConfig
    exec:
      minify:
        cmd: "php ./tools/compile.php"
      apigen:
        cmd: 'php vendor/bin/apigen.php --source ./envtesting --source ./envtests --destination ./doc/api --todo --title "Envtesting"'

    bump:
      files: ['package.json']
      push: false

  grunt.loadNpmTasks 'grunt-exec'
  grunt.loadNpmTasks 'grunt-bump'

  grunt.registerTask 'minify', ['exec:minify']
  grunt.registerTask 'apigen', ['exec:apigen']
  grunt.registerTask 'release', ['minify', 'apigen', 'bump']
  grunt.registerTask 'default', []