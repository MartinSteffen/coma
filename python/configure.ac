dnl ============================================================
dnl Version info

AC_INIT([pycoma], [0.1.0], [mky@informatik.uni-kiel.de])
AC_PREREQ([2.59])
AC_CONFIG_AUX_DIR([.])
AM_INIT_AUTOMAKE([1.9])

AC_CONFIG_SRCDIR([coma/index.py])

AC_PROG_MAKE_SET
AC_PROG_LN_S
AC_PROG_INSTALL
AM_PATH_PYTHON(2.3, false)
AC_CHECK_PROGS([CHCON], chcon, :)

dnl ===========================================================================
dnl creating files.
AC_OUTPUT([Makefile pycoma.spec coma/Makefile coma/templates/Makefile])
