package coma.servlet.util;

/** Constants for the kind of paperstates a paper can have */
public class PaperState {
	
    public static final int NOSTATE=0;
    public static final int REVIEWING =1;
    public static final int REVIEWING_CONFLICT=2;
    public static final int ACCEPTED = 3;
    public static final int REJECTED = 4;
    
    public static String number2State(int state){
    	switch (state){
    	case 0: return "NOSTATE";
    	case 1: return "REVIEWING";
    	case 2: return "REVIEWING_CONFLICT";
    	case 3: return "ACCEPTED";
    	case 4: return "REJECTED";
    	default: return "NOSTATE";
    	}
    }

}