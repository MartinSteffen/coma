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
public class AllocP_Person {

	int personID;
	int[] prefersPaper;
	int[] prefersTopic;
	int[] deniesPaper;
	int[] excludesPaper;
	Vector forbiddenPapers = new Vector();
	Vector<AllocP_Paper> papers = new Vector<AllocP_Paper>();
	int happiness = 0;
	
	public AllocP_Person(int id){
		happiness = 0;
		personID = id;
		ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
		// get prefered Papers
		SearchResult sr = db_read.getPreferedPapers(personID);
		prefersPaper = (int[]) sr.getResultObj();
		
		// get prefered Topics
		sr = db_read.getPreferedTopic(personID);
		prefersTopic = (int[]) sr.getResultObj();
		// get denied Papers
		sr = db_read.getDeniedPapers(personID);
		deniesPaper = (int[]) sr.getResultObj();
		for (int i = 0 ; i < deniesPaper.length;i++){
			forbiddenPapers.add(deniesPaper[i]);
		}
		// get excluded Papers
		sr = db_read.getExecludedPapers(personID);
		excludesPaper = (int[]) sr.getResultObj();
		for (int i = 0 ; i < excludesPaper.length;i++){
			forbiddenPapers.add(excludesPaper[i]);
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
			paper.addReviewer();
			forbiddenPapers.add(paper.getPaperID());
			foundOne = true;
		}
		return foundOne;		
	}
	
	public int[] getPreferdPapers(){
		return prefersPaper;
	}
	public int[] getPreferdTopics(){
		return prefersTopic;
	}
	public int getNumOfPapers()	{
		return papers.size();
	}
	
	public int getPersonID(){
		return personID;
	}
	
	public Vector getPapers(){
		return papers;
	}
		
}
