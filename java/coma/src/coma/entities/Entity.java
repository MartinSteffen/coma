package coma.entities;

import java.util.Collection;

/**
   Abstract superclass of all Entities.

   Provides common facilities such as XML representation.
 */
public abstract class Entity {

    /**
       All the kinds of XML representations there can be.

       2005JAN03 made public because otherwise, it's hard to call
       toXML from the outside.
     */
    public static enum XMLMODE {DEEP, SHALLOW};

    /**
       return an XML representation of this object. 

       By default, this is the deep representation.

       Be careful not to create infinite loops when calling other
       entities' toXML(), you may want toXML(XMLMODE.SHALLOW);

       Note that CharSequence is a common interface to StringBuffer,
       StringBuilder and String.
     */
    public CharSequence toXML(){
	return toXML(XMLMODE.DEEP);
    }

    /**
       Return an XML representation of this Entity. 

       If parameter isdeep equals XMLMODE.DEEP, the representation may
       be somewhat more verbose, e.g. ReviewReport might look up the
       name of its paper, etc. If mode equals XMLMODE.SHALLOW, the
       Entity should avoid doing anything fancy.

       The rationale is that for XMLMODE.DEEP, you might want to call
       toXML(XMLMODE.SHALLOW) of Entities you are very closely related
       to (Paper's author and things like that), and we have to be
       very careful about not getting infinite loops.

       CharSequence is a common superinterface to String,
       StringBuilder and StringBuffer, so you are free to use any of
       these as the return type.
     */
    public abstract CharSequence toXML(XMLMODE mode);

    /**
       Return the XML representations of all entities in the
       Collection, concatenated together.

       This is useful for things like Ratings, which come in batches.

       Note: we do not specify yet if there will be whitespace
       inserted between the representations, since that apparently
       gives XSLT problems. We do, however, currently insert a "\n"
       after each entity just in case.
    
       The result is not threadsafe.
     */
    public static CharSequence manyToXML(java.util.Collection<? extends Entity> entities, XMLMODE mode){

	StringBuilder result = new StringBuilder();
	if (entities != null){ // be extra paranoid!
	    for (Entity e: entities){
		result.append(e.toXML(mode));
		result.append('\n');
	    }
	}
	return result;
    }
}
