package coma.handler.util;

import java.sql.PreparedStatement;
import java.sql.SQLException;

import coma.entities.Conference;
import coma.entities.Criterion;
import coma.entities.Paper;
import coma.entities.Person;
import coma.entities.Rating;
import coma.entities.ReviewReport;

/**
 * @author <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z. Albari </a>
 *         Created on Dec 13, 2004 21:31:45 PM
 */
public class PreparedStatementSetter {

	public static void prepareStatement(PreparedStatement pstmt, Person person, int counter){
		try {
			pstmt.setString(++counter, person.getFirst_name());
			pstmt.setString(++counter, person.getLast_name());
			pstmt.setString(++counter, person.getAffiliation());
			pstmt.setString(++counter, person.getEmail());
			pstmt.setString(++counter, person.getPhone_number());
			pstmt.setString(++counter, person.getFax_number());
			pstmt.setString(++counter, person.getPostal_code());
			pstmt.setString(++counter, person.getCity());
			pstmt.setString(++counter, person.getState());
			pstmt.setString(++counter, person.getCountry());
			pstmt.setString(++counter, person.getPassword());
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}
	
	public static void prepareStatement(PreparedStatement pstmt,Conference conference ,int counter){
		//TODO
	}
	
	public static void prepareStatement(PreparedStatement pstmt,Criterion criterion ,int counter){
		//TODO
	}
	
	public static void prepareStatement(PreparedStatement pstmt,Paper paper ,int counter){
		//TODO
	}
	
	public static void prepareStatement(PreparedStatement pstmt,Rating rating ,int counter){
		//TODO
	}
	
	public static void prepareStatement(PreparedStatement pstmt,ReviewReport report ,int counter){
		//TODO
	}
}
