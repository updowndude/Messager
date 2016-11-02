/* @flow */
// by correy winke
// 10/17/16
import gulp from 'gulp';
import sass from 'gulp-sass';
import sourcemaps from 'gulp-sourcemaps';
import postcss from 'gulp-postcss';
import csswring from 'csswring';
import cssnext from 'postcss-cssnext';
import rucksack from 'rucksack-css';
import browserSync from 'browser-sync';
import lost from 'lost';
import connect from 'gulp-connect-php';
import dart from 'gulp-dart';

browserSync.create();

// build out css using sass and postcsss
gulp.task('sass', () => {
	const processors = [
		csswring,
		cssnext,
		rucksack,
		// simlar to bootsrap grids but has more functionality
		lost
	];

	// compile sass to css then use post css
	return gulp.src('./sass/myStyle.sass')
		.pipe(sass().on('error', sass.logError))
		.pipe(sourcemaps.write())
		.pipe(sourcemaps.write('maps', {
			includeContent: false,
			sourceRoot: 'source'
		}))
		.pipe(postcss(processors))
    .pipe(gulp.dest('public/dist'));
    // .pipe(livereload());
});

gulp.task("js", () => {
  return gulp
    .src('dart/*.dart')
    .pipe(dart({
     'dest': './public/dist',
     'minify': 'true',
		 'checked': 'true'
    }))
    .pipe(gulp.dest('./'));
});

// browserSync if chnage
gulp.task('js-watch', ['js'], () => {
	browserSync.reload();
});

gulp.task('sass-watch', ['sass'], () => {
	browserSync.reload();
});

// just gulp to start
gulp.task('default', () => {
	connect.server({}, () => {
		browserSync({
			proxy: '127.0.0.1:8000'
		});
	});

	// see if there a change
	gulp.watch('./sass/*.sass', ['sass-watch']);
	gulp.watch('./dart/*.dart', ['js-watch']);
	gulp.watch('./*.php').on('change', () => {
		browserSync.reload();
	});
	gulp.watch('./php/**/*.php').on('change', () => {
		browserSync.reload();
	});
});
