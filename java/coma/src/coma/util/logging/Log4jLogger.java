package coma.util.logging;

import java.util.Queue;

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
    private int INITBUFFERSIZE = 80;

    /** 
	Everything of this level or more severe goes into the buffer
	for potential traceback.
    */
    private Severity RETAINLEVEL = Severity.INFO;
    /**
       Everything of this level or more severe causes the buffer to be
       displayed.
     */
    private Severity FLUSHLEVEL  = Severity.ERROR;
    /**
       The size of the history buffer.
     */
    private int      HISTORYSIZE = 8;

    private final Queue<StringBuffer> history;

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

	history = new java.util.LinkedList<StringBuffer>();

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

	
	if (s.compareTo(FLUSHLEVEL) >= 0){
	    /* show history. This will empty the history as well.
	       "Fluten! Fluten!! Fluten!!!" */

	    StringBuffer m = history.poll();
	    while (m != null){

		lll.info(m);
		m = history.poll();
	    }
	}

	if (s.compareTo(RETAINLEVEL) >= 0){
	    /* store log message */
	    history.offer(new StringBuffer("HISTORY: ")
			  .append(s.toString())
			  .append(' ')
			  .append(sb));
	    while (history.size() > HISTORYSIZE){
		history.poll();
	    }
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
