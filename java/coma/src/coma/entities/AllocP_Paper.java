/*
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
public class AllocP_Paper  {
	
		int paperID;
		int[] topicIDs = new int[0];
		int numOfReviewer =0;
		String title = "";
		Vector<AllocP_Person> reviewer = new Vector<AllocP_Person>();
		
		public AllocP_Paper(int id){
			paperID = id;
			ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
			SearchResult resultset;
			resultset = db_read.getAllTopicsOfPaper(paperID);
			topicIDs = (int[])resultset.getResultObj();
			SearchCriteria sr = new SearchCriteria();
			sr.setPaper(new Paper(paperID));
			resultset = db_read.getPaper(sr);
			Paper[] pap = (Paper[]) resultset.getResultObj();
			title = pap[0].getTitle();
		}
	
		public void addReviewer(AllocP_Person rev){
			numOfReviewer++;
			reviewer.add(rev);
		}
		
		public boolean isOpen(int min){
			if (numOfReviewer < min) return true;
			return false;
		}
		
		public int getNumOfReviewer(){
			return numOfReviewer;
		}
		
		public int getPaperID(){
			return paperID;
		}
		public int[] getTopicIDs(){
			return topicIDs;
		}

		/**
		 * 
		 */
		public void resetRewiever() {
			numOfReviewer =0;
			reviewer = new Vector<AllocP_Person>();
		}

		/**
		 * @return
		 */
		public String getTitle() {
			return title;
		}

		/**
		 * @return
		 */
		public Vector<AllocP_Person> getReviewer() {
			return reviewer;
		}
}
