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
public class AllocP_Paper {
	
		int paperID;
		int[] topicIDs = new int[0];
		int numOfReviewer =0;
		
		public AllocP_Paper(int id){
			paperID = id;
			ReadService db_read = new coma.handler.impl.db.ReadServiceImpl();
			SearchResult resultset;
			resultset = db_read.getAllTopicsOfPaper(paperID);
			topicIDs = (int[])resultset.getResultObj();
		}
		
		public void addReviewer(){
			numOfReviewer++;
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
}
