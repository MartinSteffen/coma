package coma.util.logging;
/**
   Abstract superclass of the coma logging mechanism.

   Also, this class is its own Factory and offers a static instance
   for lazy people.

   @author: ums
 */

public abstract class ALogger {

    /** do-nothing constructor */
    ALogger(String extraName){;}

    /**
       Maybe log the String reprentations of the whats with severity
       level s. 

       It is legal for implementations to just throw it all away.
     */
    public abstract void log(Severity s, Object... what);

    /**
       Static convenience instance of a logger. 

       If you don't want to create your own, you can say
       <code>ALogger.log.log(Severity.FATAL,"Boom!")</code>
     */
    public static ALogger log = create("(any)");


    /**
       Static factory for your ALoggers.

       Takes a "name" that will in some way be associated with the log
       output. It's possibly a good idea to use the class or method
       name.

       We return a log4j logger if we can, otherwise we fall back to a
       simple stderr logger
     */
    public static ALogger create(String extraName){

	try {
	    Class.forName("org.apache.log4j.Logger");
	    return new Log4jLogger(extraName);
	} catch (ClassNotFoundException cnfe) {
	    // No, I can't find log4j
	    return new StdErrLogger(extraName);
	} catch (Throwable tbl) {
	    throw new Error(tbl);
	}

    }

    @SuppressWarnings(value="all") /* calm down, it's only test routines */
    public static void main(String[] args){
	
	create("boing!").log(Severity.FATAL,
			     "It", "works", 42, new StdErrLogger(null));
	ALogger.log.log(Severity.DEBUG, 
			"Static", "and", "ecstatic");
    }
}
