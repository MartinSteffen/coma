package coma.servlet.util;

import coma.entities.ReviewReport;
import coma.entities.Criterion;
import coma.entities.Rating;
import java.util.*;

/**
   A helper class for statistics.

   Throw in a lot of ReviewReports, get out average and rms-estimate
   for each Criterion involved.

   we use the following formula for rms, when we have n reports, and i
   always runs over n:

   mean = 1/n \sum_i x_i

   rms  = sqrt((N\sum_i x_i^2 -(\sum_i x_i)^2 )/n) 

   TODO: Currently, we do not care about those priority factors.
*/
public class MultiMathReporter {

    /** A dummy var that indicates Array of Integer to Collection.toArray*/
    private static final Integer[] INTARRAY_MOLD=new Integer[0];

    java.util.Map<String, Collection<Integer>> ratings
	= new TreeMap<String, Collection<Integer>>();

    java.util.Map<String, Integer> maxvals
	= new TreeMap<String, Integer>();

    java.util.Map<String, Integer> prios
	= new TreeMap<String, Integer>();

    
    public MultiMathReporter(){;}

    /**
       put in another ReviewReport that should go into the maths.
    */
    public void addReportRatings(ReviewReport rr){
	for (Rating rat: rr.getRatings()){

	    Criterion crit = rat.getCriterion();
	    
	    Collection<Integer> grades = ratings.get(crit.getName());
	    if (grades==null){
		grades = new java.util.ArrayList<Integer>();
	    }
	    grades.add(rat.getGrade());
	    ratings.put(crit.getName(), grades);
	    maxvals.put(crit.getName(), crit.getMaxValue());
	    prios.put(crit.getName(), crit.getQualityRating());
	}
    }

    /**
       calculate and return all that we know.
    */
    public CharSequence toXML(){
	if (ratings.keySet().size()==0)
	    return "";
	StringBuilder result = new StringBuilder();
	result.append("<statistics>");
	for (String critname: ratings.keySet()){
	    result.append("<criterion name=\""+critname+"\">");
	    result.append(XMLHelper.tagged("mean", mean(ratings.get(critname).toArray(INTARRAY_MOLD))));
	    result.append(XMLHelper.tagged("rms", rms(ratings.get(critname).toArray(INTARRAY_MOLD))));
	    result.append("</criterion>");
	}
	result.append("</statistics>");
	return result;
    }

    /**
       Return the mean of all passed integers that are at least 1.
       
       We skip all other integers. This means that this is safe for
       almost any kind of initialisation the Chair people make up for
       Reports.
    */
    Double mean(Integer... xs){
	if ((xs==null) 
	    || (xs.length ==0)) return 0.0;
	Double result = 0.0;
	int n = 0;
	for (Integer x: xs){
	    if (x==null) continue;
	    if (x >= 1){
		result += x;
		n++;
	    }
	}
	return result/(1.0*n);
    }

    /**
       Return the rms of all passed integers that are at least 1.
       
       We skip all other integers. This means that this is safe for
       almost any kind of initialisation the Chair people make up for
       Reports.

       rms is a measure of the deviation of the points, i.e. high
       values are a sign of equivocality.
    */
    Double rms(Integer... xs){
	if ((xs==null) 
	    || (xs.length ==0)) return 0.0;
	Double sqsum = 0.0;
	Double sum = 0.0;
	double n = 0;
	for (Integer x:xs){
	    if (x==null) continue;
	    if (x >= 1){
		sum += x;
		sqsum += (x*x);
		n++;
	    }
	}
	return Math.sqrt((n*sqsum - sum*sum)/(n*n));
	
    }

}
