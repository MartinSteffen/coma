package coma.util.logging;
/**
   A very simple fallback logger.

   It just dumps everything to stdout, always.
 */
class StdErrLogger extends ALogger {

    private final int INITBUFFERSIZE = 80;

    private String extraName;
    private StringBuffer sb = new StringBuffer(INITBUFFERSIZE);

    StdErrLogger(String extraName){
	super(extraName);
	this.extraName = extraName;
    }

    /**
       Log the whats with severity s.
     */
    public void log(Severity s, Object... what){

	sb.delete(0, sb.length());
	sb.append(s.toString());
	if (extraName != null){
	    sb.append(" in ");
	    sb.append(extraName);
	}
	sb.append(": ");
	for (Object wha: what){
	    sb.append(wha.toString());
	    sb.append(' ');
	}
	
	System.err.println(sb.toString());
    }

}
