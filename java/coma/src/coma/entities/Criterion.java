package coma.entities;

/**
   Wrapper for entries in the DB's Criterion table

   @author ums
 */
public class Criterion extends Entity {

    /* the DB's fields. */
    private final int id;
    private final int conferenceId;
    private final String name;
    private final String description;
    private final int maxValue;
    private int qualityRating;

    public Criterion(int id){
	/* FIXME: get criterion out of database by ID */
    }

    public int getId(){return id;}
    public int getConferenceId(){return conferenceId;}
    public String getName(){
	/* String is immutable, so we don't have to worry that the
	 * world can modify our field if we return this directly
	 * instead of a clone.
	 */
	return name;
    }
    public String getDescription(){
	/* cf. getName() on why we don't clone.*/
	return description;
    }
    public int getMaxValue(){return maxValue;}
    public int getQualityRating(){return qualityRating;}

    public static Conference getConference(){
	/* FIXME unclear what the params of E~C~ should do. */
	return new coma.entity.util.EntityCreator().getConference(null);
    }
}
