(TeX-add-style-hook "macros"
 (function
  (lambda ()
    (LaTeX-add-environments
     "diagram")
    (TeX-add-symbols
     '("sizeof" 1)
     '("team" 1)
     "NONAME"
     "Coma"
     "cvs"
     "Java"
     "Cplusplus"
     "javadoc"
     "bnfdef"
     "bnfbar"
     "suchthat"
     "union"
     "intersect"
     "sem"))))

