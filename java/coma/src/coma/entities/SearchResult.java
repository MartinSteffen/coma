
package coma.entities;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Albari</a>
 * Created on Dec 2, 2004  11:03:24 PM
 * 
 * <p>
 * All searching methodes will return theres results as a SearchResult 
 * object. So if you,for eample, call coma.handler.db.impl.getConference(SearchCriretia)
 * the result,an array Conference[], will be included in a searchCriteria.
 * </p>
 */

public class SearchResult {

    public SearchResult(){
    }
    
    public Object resultObj;
    public String info;
    public boolean SUCCESS;
    
    /**
     * Gives a message, that will be created during
     * performing a transaction. This message gives informations
     * like a database error-message or a message that some parameter
     * was not set or an exception-message that was thrown while
     * performing transaction. 
     * @return Returns the info.
     */
    public String getInfo() {
        return info;
    }
    /**
     * @param info The info to set.
     */
    public void setInfo(String info) {
        this.info = info;
    }
    /**
     * When searching the Database for some Dataset like
     * Conferences, Papers, Persons, etc. search result
     * will be an array that ca be retreived it call from 
     * this methode.
     * @return Returns the resultObj.
     */
    public Object getResultObj() {
        return resultObj;
    }
    /**
     * indicate whether performed transaction was successfully.
     * @return Returns the sUCCESS.
     */
    public  boolean isSUCCESS() {
        return SUCCESS;
    }
    /**
     * @param success The sUCCESS-flag to set.
     */
    public void setSUCCESS(boolean success) {
        SUCCESS = success;
    }
    /**
     * @param resultObj The resultObj to set.
     */
    public void setResultObj(Object resultObj) {
        this.resultObj = resultObj;
    }
    
}
