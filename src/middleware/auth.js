// src/middleware/auth.js

// Cek apakah user sudah login
function isLoggedIn(req, res, next) {
  if (req.session && req.session.user) {
    return next();
  }
  req.flash('error', 'Silakan login terlebih dahulu.');
  return res.redirect('/auth/login');
}

// Cek apakah user adalah mentor
function isMentor(req, res, next) {
  if (req.session && req.session.user && req.session.user.role === 'mentor') {
    return next();
  }
  req.flash('error', 'Halaman ini hanya untuk Mentor.');
  return res.redirect('/dashboard');
}

// Cek apakah user adalah admin
function isAdmin(req, res, next) {
  if (req.session && req.session.user && req.session.user.role === 'admin') {
    return next();
  }
  req.flash('error', 'Akses ditolak.');
  return res.redirect('/dashboard');
}

// Cek apakah user adalah student
function isStudent(req, res, next) {
  if (req.session && req.session.user && req.session.user.role === 'student') {
    return next();
  }
  req.flash('error', 'Halaman ini hanya untuk Pelajar.');
  return res.redirect('/dashboard');
}

// Tambahkan data user ke semua views (harus dipasang di app.js)
function setLocals(req, res, next) {
  res.locals.user = req.session.user || null;
  res.locals.flash_success = req.flash('success');
  res.locals.flash_error = req.flash('error');
  next();
}

module.exports = { isLoggedIn, isMentor, isAdmin, isStudent, setLocals };
