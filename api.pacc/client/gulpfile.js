var gulp = require("gulp"),
  sass = require("gulp-sass"),
  babel = require("gulp-babel"),
  sourcemaps = require("gulp-sourcemaps"),
  prefix = require("gulp-autoprefixer"),
  uglify = require("gulp-uglify"),
  watch = require("gulp-watch"),
  changed = require("gulp-changed"),
  csso = require("gulp-csso"),
  through2 = require("through2"),
  fs = require("fs"),
  del = require("del"),
  uglifyOptions = {
    toplevel: false,
  };

var touch = function () {
  return through2.obj(function (file, enc, cb) {
    if (file.isNull()) {
      return cb(null, file);
    }

    // update file modification and access time
    return fs.utimesSync(file.path, new Date(), new Date());
  });
};

var clean = function (cb) {
  del.sync(["dist/"]);
  cb();
};

var scss = function (cb) {
  gulp
    .src("src/scss/index.scss")
    .on("end", function () {
      cb();
    })
    .pipe(sass().on("error", sass.logError))
    .pipe(prefix("cover 99.5%"))
    .pipe(csso())
    .pipe(gulp.dest("dist/css"));
};

var js = function (cb) {
  gulp
    .src("src/js/**/*.js")
    .on("end", function () {
      cb();
    })
    .pipe(babel())
    .pipe(uglify(uglifyOptions))
    .pipe(gulp.dest("dist/js"));
};

var all = gulp.parallel(scss, js);

// run $ gulp
gulp.task("default", gulp.series(clean, all));

// run $ gulp watch
gulp.task("watch", function () {
  watch("src/scss/**/*.scss", { verbose: true }, function () {
    gulp
      .src("src/scss/index.scss")
      .pipe(sourcemaps.init())
      .pipe(changed("dist/css"))
      .pipe(sass().on("error", sass.logError))
      .pipe(sourcemaps.write())
      .pipe(gulp.dest("dist/css"))
      .pipe(touch());
  });

  watch("src/js/**/*.js", { verbose: true }, function () {
    gulp
      .src("src/js/**/*.js")
      .pipe(changed("dist/js"))
      .pipe(babel())
      // .pipe(uglify(uglifyOptions))
      .pipe(gulp.dest("dist/js"))
      .pipe(touch());
  });
});
