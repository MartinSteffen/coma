/*
 * Created on 27.01.2005
 *
 */
package coma.entities;


import java.util.Enumeration;
import java.util.HashSet;
import java.util.Hashtable;
import java.util.Vector;

import coma.handler.db.ReadService;

/**
 * @author ald
 *
 */
public class AllocP_PaperList {

	Hashtable<Integer,AllocP_Paper> papers_by_paperID = new Hashtable<Integer,AllocP_Paper>();
	Hashtable<Integer,Vector> papers_by_topicID = new Hashtable<Integer,Vector>();
	int min_reviewer = 0;
	
	public AllocP_PaperList(int conference_id,int min_rev){
		
		min_reviewer = min_rev;
		ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
		SearchResult resultset;
		resultset = db_read.getAllPapers(conference_id);
		
		int[] list = (int[])resultset.getResultObj();
		for (int i = 0; i <list.length;i++){
			addPaper(new AllocP_Paper(list[i]));
		}
	}
	
	public void addPaper(AllocP_Paper paper){
		int p_id = paper.getPaperID();
		int[] t_id = paper.getTopicIDs();
		papers_by_paperID.put(p_id,paper);
		for (int i = 0; i < t_id.length;i++){
		if (papers_by_topicID.get(t_id[i]) == null) {
			Vector v = new Vector();
			v.add(paper);
			papers_by_topicID.put(t_id[i],v);
		}
		else 
			{
			Vector v = (Vector)papers_by_topicID.get(t_id[i]);
			v.add(paper);
			papers_by_topicID.put(t_id[i],v);
			}
		}
	}
	
	public int getOpenPapers(){
		Enumeration<AllocP_Paper> e = getPapers();
		int open_papers = 0;
		while (e.hasMoreElements())
		{
			AllocP_Paper p = e.nextElement();
			if (p.isOpen(min_reviewer)) open_papers++; 
		}
		return open_papers;
	}
	
	public AllocP_Paper findPaperForMe(AllocP_Person person,boolean open){
		AllocP_Paper paper;
		if ((paper = findPaperbyPaperID(person,open)) != null )
		{
			person.incHappiness();
			person.incHappiness();
			return paper;
		}
		if ((paper = findPaperbyTopicID(person,open)) != null )
		{
			person.incHappiness();
			return paper;
		}
		paper = findOpenPaper();
		return paper;
	}

	/**
	 * @return
	 */
	private AllocP_Paper findOpenPaper() {
		Enumeration<AllocP_Paper> e = getPapers();
		while (e.hasMoreElements())
		{
			AllocP_Paper p = e.nextElement();
			if (p.isOpen(min_reviewer)) return p; 
		}
		return null;
	}

	/**
	 * @param person
	 * @return
	 */
	private AllocP_Paper findPaperbyTopicID(AllocP_Person person,boolean open) {
		int[] topicIDs = person.getPreferdTopics();
		HashSet<AllocP_Paper> paperlist = new HashSet<AllocP_Paper>();
		for (int i = 0 ; i < topicIDs.length;i++){
			Vector p = (Vector) papers_by_topicID.get(topicIDs[i]);
			for (int j = 0 ; j < p.size();j++){
				AllocP_Paper paper = (AllocP_Paper) p.elementAt(j);
				if (paper.isOpen(min_reviewer) || 
						( !open && paper.isOpen(min_reviewer+1))) 
							paperlist.add(paper);
			}			
		}
		Object[] openpapers = paperlist.toArray();
		int count = 0;
		boolean ok = false;
		AllocP_Paper paper = null;
		while (count < openpapers.length && !(ok = person.verifyPaper(paper = (AllocP_Paper) openpapers[count])))			
					count++;			
		
		if (ok) return paper;
		return null;
	}

	/**
	 * @param person
	 * @return
	 */
	private AllocP_Paper findPaperbyPaperID(AllocP_Person person, boolean open) {
		int[] preferedPapers = person.getPreferdPapers();
		int count = 0;
		boolean ok = false;
		AllocP_Paper paper = null;
		while (count < preferedPapers.length &&
				!(ok = person.verifyPaper(paper = findOpenPaperByPaperID(preferedPapers[count],open))))			
					count++;			
		
		if (ok) return paper;
		return null;
	}

	/**
	 * @param i
	 * @return
	 */
	private AllocP_Paper findOpenPaperByPaperID(int id,boolean open) {
		AllocP_Paper paper = null;
		if (papers_by_paperID.containsKey(id))
			paper = (AllocP_Paper) papers_by_paperID.get(id);
		if (paper.isOpen(min_reviewer) ||
				( !open && paper.isOpen(min_reviewer+1)))
						return paper;
		return null;
	}
	public Enumeration<AllocP_Paper> getPapers(){
		return papers_by_paperID.elements();
	}
}