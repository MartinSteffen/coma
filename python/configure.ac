dnl ============================================================
dnl Version info

AC_INIT([CoMa-Python], [0.1.0], [mky@informatik.uni-kiel.de])
AC_PREREQ([2.59])
AC_CONFIG_AUX_DIR([.])
AM_INIT_AUTOMAKE([1.9])

AC_CONFIG_SRCDIR([coma/comadb.py])

AC_PROG_MAKE_SET
AC_PROG_LN_S
AC_PROG_INSTALL
AM_PATH_PYTHON(2.3, false)
AC_CHECK_PROGS([M4], m4, false)
AC_CHECK_PROGS([AUTOM4TE], autom4te, false)
AC_CHECK_PROGS([DIFF], diff, false)
AC_CHECK_PROGS([CHCON], chcon, :)

comadir=${datadir}/coma
AC_SUBST([comadir])

dnl ===========================================================================
dnl Test suite configuration.
dnl AC_CONFIG_TESTDIR(tests, suml)


dnl ===========================================================================
dnl creating files.
AC_OUTPUT([Makefile coma/Makefile coma/templates/Makefile])
