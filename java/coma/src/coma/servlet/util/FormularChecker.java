
package coma.servlet.util;

/**
 * @author pka
 *
 * Class for checking formulars.  
 */
public class FormularChecker {

	private String[] data;
	private boolean check;
	
	/**
	 * @author pka
	 *
	 * @param d: the formular fields to check
	 */	
	public FormularChecker(String[] d)
	{
		data = d;
	}
	
	/**
	 * @author pka
	 *
	 * @return check: true, if there is no empty formular field
	 */	
	
	public boolean check()
	{
		check = true;
		for (int i=0;i<data.length;i++)
		{
			System.out.println("data= " + data[i]);
			if (data[i].equals(""))
			{
			    check = false;
				return check;
			}
		}
		return check;
	}
}
