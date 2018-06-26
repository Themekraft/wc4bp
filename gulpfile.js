/**
 * Gulpfile.
 *
 * A simple implementation of Gulp.
 *
 * Implements:
 *            1. CSS concatenation and minification
 *            2. JS concatenation
 *            3. Watch files
 *
 * @since 3.1.6
 * @author Guillermo Figueroa Mesa (@gfirem)
 */

/**
 * Configuration.
 */
const styleDir = './admin/css/*.css';
const styleDestination = './admin/css/';
const jsDir = './admin/js/*.js';
const jsDestination = './admin/js/';
const styleWatchFiles = './admin/css/*.css';
const jsWatchFiles = './admin/js/*.js';
const languagesFolder = './languages/';
const languages = ['es', 'fr', 'nb'];
/**
 * Load modules
 */
require('dotenv').config();
const gulp = require('gulp');
const minifycss = require('gulp-uglifycss');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const notify = require('gulp-notify');
const clean = require('gulp-clean');
const stripDebug = require('gulp-strip-debug');
const eslint = require('gulp-eslint');
const wpPot = require('gulp-wp-pot');
const pofill = require('gulp-pofill');
const gettext = require('gulp-gettext');
const googleTranslate = require('google-translate')(process.env.GOOGLE_API);

gulp.task('lint', () => {
    return gulp.src([jsDir, '!node_modules/**'])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

//Extract localization strings and translate it with google
gulp.task('prepare-localization', function() {
    gulp.src('./**/*.php')
        .pipe(wpPot({
            domain: 'wc4bp',
            package: 'WooCommerce BuddyPress Integration',
            bugReport: 'https://github.com/Themekraft/wc4bp/issues',
            lastTranslator: 'Sven Lehnert <svenl@themekraft.com>',
            team: 'ThemeKraft Team <svenl@themekraft.com>',
            gettextFunctions: [
                {name: '__'},
                {name: '_e'},
                {name: 'esc_html__'},
                {name: 'esc_html_e'},
                {name: '_x', context: 2},
                {name: '_esc_attr'},
                {name: '_esc_attr_echo'},
                {name: '_esc_html'},
                {name: '_esc_html_echo'},
                {name: '_ex', context: 2},
                {name: '_esc_attr_x', context: 2},
                {name: '_esc_html_x', context: 2},
                {name: '_n', plural: 2},
                {name: '_n_noop', plural: 2},
                {name: '_nx', plural: 2, context: 4},
                {name: '_nx_noop', plural: 2, context: 3}
            ]
        }))
        .pipe(gulp.dest(languagesFolder + 'wc4bp.pot'));
    //Create the english po file
    return gulp.src(languagesFolder + 'wc4bp.pot')
        .pipe(pofill({
            items: function(item) {
                // If msgstr is empty, use identity translation
                if (!item.msgstr.length) {
                    item.msgstr = [''];
                }
                if (!item.msgstr[0]) {
                    item.msgstr[0] = item.msgid;
                }
                return item;
            }
        }))
        .pipe(rename('wc4bp-en.po'))
        .pipe(gulp.dest(languagesFolder));
});

//Extract localization strings and translate it with google
gulp.task('translate', ['prepare-localization'], function() {
    let task = [];
    //Create the es po file
    languages.forEach((lang) => {
        task.push(
            gulp.src(languagesFolder + 'wc4bp.pot')
                .pipe(pofill({
                    items: function(item) {
                        return new Promise((resolve) => {
                            // If msgstr is empty, use identity translation
                            if (!item.msgstr.length) {
                                item.msgstr = [''];
                            }
                            if (!item.msgstr[0]) {
                                googleTranslate.translate(item.msgid, lang, function(err, translation) {
                                    if (translation && translation.translatedText) {
                                        item.msgstr[0] = translation.translatedText;
                                    }
                                    resolve(item);
                                });
                            } else {
                                resolve(item);
                            }
                        });
                    }
                }))
                .pipe(rename('wc4bp-' + lang + '_' + lang.toUpperCase() + '.po'))
                .pipe(gulp.dest(languagesFolder))
        );
    });
    return task;
});

// Compile *.po to *.mo binaries for usage.
gulp.task('compile-translations', ['translate'], function() {
    return gulp.src(languagesFolder + '*.po')
        .pipe(gettext())
        .pipe(gulp.dest(languagesFolder))
});

gulp.task('clean-min-styles', function() {
    return gulp.src(styleDestination + '*.min.css', {read: false})
        .pipe(clean({force: true}));
});

gulp.task('clean-min-js', function() {
    return gulp.src(jsDestination + '*.min.js', {read: false})
        .pipe(clean({force: true}));
});

gulp.task('styles', ['clean-min-styles'], function() {
    gulp.src(styleDir)
        .pipe(rename({suffix: '.min'}))
        .pipe(minifycss({
            maxLineLen: 10
        }))
        .pipe(gulp.dest(styleDestination))
        .pipe(notify({message: 'TASK: "CSS" Completed!', onLast: true}))
});

gulp.task('js', ['clean-min-js'], function() {
    gulp.src(jsDir)
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(stripDebug())
        .pipe(gulp.dest(jsDestination))
        .pipe(notify({message: 'TASK: "JS" Completed!', onLast: true}));
});

gulp.task('default', ['styles', 'lint', 'js', 'prepare-localization'], function() {
    gulp.run('styles');
    gulp.run('lint');
    gulp.run('js');
    gulp.run('prepare-localization');
    gulp.run('translate');
    gulp.run('compile-translations');
});