/*
 * Created on 31.01.2005
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
package coma.entities;

import java.util.Collection;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.Vector;

/**
 * @author sascha
 *
 * TODO To change the template for this generated type comment go to
 * Window - Preferences - Java - Code Style - Code Templates
 */
public class Allocation {

		int happiness = 0;
		Hashtable<Integer,int[]> alloc = new Hashtable<Integer,int[]>();
		Hashtable<Integer,Integer> reviews_paper 
				= new Hashtable<Integer,Integer>();
				
		public Allocation(AllocP_PaperList papers, AllocP_Person[] persons){
			Collection<AllocP_Paper> pap = papers.getPapers();
			Iterator<AllocP_Paper> it = pap.iterator();
			while (it.hasNext()){
				AllocP_Paper p = it.next();
				reviews_paper.put(p.getPaperID(),p.getNumOfReviewer());
			}
			
			for (int i = 0; i < persons.length ; i++){
				Vector<AllocP_Paper> v = persons[i].getPapers();
				int[] p = new int[v.size()];
				for (int j = 0 ; j < v.size();j++){
					p[j] = (v.elementAt(j)).getPaperID();
				}
				alloc.put(persons[i].getPersonID(),p);
				
			}
		}
		/**
		 * 
		 */
		public Allocation() {
			
			
		}
		
		/**
		 * @param paperList
		 * @param personList
		 */
		public void reAlloc(AllocP_PaperList paperList, 
							AllocP_Person[] personList) {
			Collection<AllocP_Paper> pap = paperList.getPapers();
			Iterator<AllocP_Paper> it = pap.iterator();
			while(it.hasNext()){
				AllocP_Paper p = it.next();
				p.numOfReviewer = reviews_paper.get(p.getPaperID());
			}
			for (int i = 0 ; i < personList.length; i++){
							
				Vector<AllocP_Paper> v = new Vector<AllocP_Paper>();
				
				int[] papersOfPerson = alloc.get(personList[i].getPersonID());
				for (int j = 0 ; j < papersOfPerson.length;j++){
					v.add(paperList.getPaperByID(papersOfPerson[j]));
					
				}
				personList[i].setPapers(v);
				personList[i].reCalcHapCon();
			}
		}
	
}
