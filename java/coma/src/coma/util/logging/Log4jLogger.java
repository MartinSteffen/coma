package coma.util.logging;
/**
   Wrapper class for Log4J.

   Currently, we use some sort of default console logging thing.
   Use ALogger.create(...) to obtain an instance of this.

   @author ums
 */
class Log4jLogger extends ALogger{

    static {
	/* maybe initialise log4j */
	try{
	    Class.forName("org.apache.log4j.BasicConfigurator")
		.getDeclaredMethod("configure").invoke(null);
	} catch (Throwable tbl) {
	    /* Bummsfallera, dann tut's halt nicht. */
	    /* 
	       whoopsie! No log4j, 
	       later fall back onto stderrlogger
	    */
	}

    }

    /** 
	initial stringbuffer capacity for temporary stringbuffer. For
	debugging, the Java default of 16 is probably too little.
     */
    private final int INITBUFFERSIZE = 80;


    /** the low level logger of l4j */
    private org.apache.log4j.Logger lll = null;

    /**
       we assemble our output in this.
     */
    private StringBuffer sb = new StringBuffer(INITBUFFERSIZE);

    Log4jLogger(String extraName){
	super(extraName);
	try {
	    lll = (org.apache.log4j.Logger)
		Class.forName("org.apache.log4j.Logger")
		.getDeclaredMethod("getLogger", String.class)
		.invoke(null, extraName);
	} catch (Throwable tbl) {

	    System.err.println("now this can't happen at all!");
	}

    }

    /**
       Log the whats with severity s.
     */
    public void log(Severity s, Object... what){

	sb.delete(0, sb.length());
	for (Object wha: what){
	    sb.append(wha.toString());
	    sb.append(' ');
	}

	switch (s){

	case DEBUG : lll.debug(sb); break;
	case INFO  : lll.info (sb); break;
	case WARN  : lll.warn (sb); break;
	case ERROR : lll.error(sb); break;
	case FATAL : lll.fatal(sb); break;

	}

    }


}
