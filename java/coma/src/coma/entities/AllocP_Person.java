/*
 *
 * 
 */
package coma.entities;

import java.util.Vector;

import coma.handler.db.ReadService;

/**
 * @author ald
 *
 * 
 */
public class AllocP_Person  {

	int personID;
	//mglw gegen Vectoren tauschen.
	Vector<Integer> prefersPaper = new Vector<Integer>();
	Vector<Integer> prefersTopic = new Vector<Integer>();
	Vector<Integer> deniesPaper = new Vector<Integer>();
	Vector<Integer> excludesPaper = new Vector<Integer>();
	Vector forbiddenPapers = new Vector();
	Vector<AllocP_Paper> papers = new Vector<AllocP_Paper>();
	int happiness = 0;
	int contentment = 0;
	String first_name = "";
	String last_name = "";
	String email = "";
	String title = "";
	
	public AllocP_Person(int id){
		happiness = 0;
		personID = id;
		ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
		// get prefered Papers
		Person per = new Person(id);
		per.setId(personID);
		SearchCriteria sc = new SearchCriteria();
		sc.setPerson(per);
		SearchResult sr = db_read.getPerson(sc);
		per = ((Person[])sr.getResultObj())[0];
		first_name = per.getFirst_name();
		last_name = per.getLast_name();
		email = per.getEmail();
		title = per.getTitle();
		sr = db_read.getPreferedPapers(personID);
		int []prefP = (int[]) sr.getResultObj();
		for (int i = 0;i < prefP.length;i++)
			prefersPaper.add(prefP[i]);
		// get prefered Topics
		sr = db_read.getPreferedTopic(personID);
		int []prefT = (int[]) sr.getResultObj();
		
		for (int i = 0;i < prefT.length;i++)
			prefersTopic.add(prefT[i]);
		// get denied Papers
		sr = db_read.getDeniedPapers(personID);
		int []denP = (int[]) sr.getResultObj();
		for (int i = 0 ; i < denP.length;i++){
			forbiddenPapers.add(denP[i]);
			deniesPaper.add(denP[i]);
			
		}
		// get excluded Papers
		sr = db_read.getExecludedPapers(personID);
		int[] exclP = (int[]) sr.getResultObj();
		for (int i = 0 ; i < exclP.length;i++){
			forbiddenPapers.add(exclP[i]);
			excludesPaper.add(exclP[i]);
		}
	}
	
	public void resetHappy(){
		happiness = 0;
	}
	
	public void incHappiness(){
		happiness++;
	}
	
	public int getHappiness(){
		return happiness;
	}
	
	public int getPercHappiness(){
		return (int) ((float)happiness/(((float)papers.size())*2)*100);
	}
	
	public boolean verifyPaper(AllocP_Paper paper){
	    if (paper == null) return false;
		int paper_id = paper.getPaperID();
		boolean ok = !(forbiddenPapers.contains(paper_id));
		return ok;
	}
	
	public boolean pickPaper(AllocP_PaperList paperlist,boolean open){
		boolean foundOne = false;
		AllocP_Paper paper = paperlist.findPaperForMe(this,open);
		
		if (paper != null)
		{
			papers.add(paper);
			paper.addReviewer(this);
			forbiddenPapers.add(paper.getPaperID());
			foundOne = true;
			reCalcHapCon();
		}
		return foundOne;		
	}
	
	/**
	 * 
	 */
	public void reCalcHapCon() {
		this.resetHappy();
		this.resetContent();
		for (int i = 0; i < papers.size() ; i++){
			if (this.isPreferedPaper(papers.elementAt(i))){
				this.incHappiness();
				this.incHappiness();
				this.incContent();
			}
			else if (this.isPreferedTopic(papers.elementAt(i))){
				this.incHappiness();
				this.incContent();
			}
			else if (this.prefersPaper.size() == 0 && this.prefersTopic.size() == 0)
				this.incContent();
		}
		
	}

	public boolean isPreferedPaper(AllocP_Paper p){
		return this.prefersPaper.contains(p.getPaperID());
	}
	
	public boolean isPreferedTopic(AllocP_Paper p){
		boolean isPref = false;
		int [] tops = p.getTopicIDs();
		for (int i = 0; i < tops.length ; i++)			
			if (isPref = this.prefersTopic.contains(tops[i])) return isPref;
		return isPref;
	}
	
	public Vector<Integer> getPreferdPapers(){
		return prefersPaper;
	}
	
	public Vector<Integer> getPreferdTopics(){
		return prefersTopic;
	}
	
	public int getNumOfPapers()	{
		return papers.size();
	}
	
	public int getPersonID(){
		return personID;
	}
	
	public Vector<AllocP_Paper> getPapers(){
		return papers;
	}
	
	public void setPapers(Vector<AllocP_Paper> p){
		papers = p;
	}
	/**
	 * 
	 */
	public void resetPapers() {
		papers = new Vector<AllocP_Paper>();
		forbiddenPapers = new Vector();
		for (int i = 0 ; i < deniesPaper.size();i++){
			forbiddenPapers.add(deniesPaper.elementAt(i));
		}
		
		for (int i = 0 ; i < excludesPaper.size();i++){
			forbiddenPapers.add(excludesPaper.elementAt(i));
		}
	}

	/**
	 * @param integer
	 */
	public void setHappiness(Integer happy) {
		happiness = happy;
		
	}

	/**
	 * @return
	 */
	public int getPercContent() {
		
		return (contentment*100)/getNumOfPapers();
	}

	/**
	 * @return
	 */
	public int getContent() {
		
		return contentment;
	}

	/**
	 * 
	 */
	public void incContent() {
		contentment++;
		
	}

	/**
	 * @param integer
	 */
	public void setContent(int c) {
		contentment = c;
		
	}

	/**
	 * 
	 */
	public void resetContent() {
		contentment = 0;
	}

	/**
	 * @return
	 */
	public String getFirstName() {
		
		return first_name;
	}

	/**
	 * @return
	 */
	public String getLastName() {
	
		return last_name;
	}
	public String getTitle(){
		return title;
	}
	
	
}
