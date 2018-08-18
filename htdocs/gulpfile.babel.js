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
import lost from 'lost';
import livereload from 'gulp-livereload';

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
    .pipe(gulp.dest('public/dist'))
    .pipe(livereload());
});

gulp.task('index', () => {
	return gulp.src('./index.php')
		.pipe(livereload());
});

gulp.task('php', () => {
	return gulp.src('php/**/*.php')
		.pipe(livereload());
});	

gulp.task('publicDist', () => {
	return gulp.src('public/dist/*.js')
		.pipe(livereload());
});	

gulp.task('default', () => {
	livereload.listen();
	gulp.watch('./sass/*.sass', ['sass']);
	gulp.watch('index.php', ['index']);
	gulp.watch('php/**/*.php', ['php']);
	gulp.watch('public/dist/*.js', ['publicDist']);
});
