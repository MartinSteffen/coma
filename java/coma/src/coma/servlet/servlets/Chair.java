package coma.servlet.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringReader;
import java.util.GregorianCalendar;
import java.io.*;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.xml.transform.stream.StreamSource;

import coma.entities.Conference;
import java.util.Vector;
import coma.entities.*;
import coma.servlet.util.XMLHelper;
import coma.handler.impl.db.*;
import coma.servlet.util.*;
import coma.util.logging.ALogger;
import static coma.util.logging.Severity.ERROR;

import java.util.*;


/**
 * @author Peter Kauffels & Harm Brandt
 * @version 1.0
 * The chair servlet is responsible for all functions the chair can do.
   Changes at 12.12: 	- added commit Method(at the bottom).
   by hbra@inf...	    - added EMail function(not yet for invite)
   						- changes path settings
   						- added if ( action == null) at 67
 * Changes at 14.12: 	- change method commit, now runs with parameters 
 * by pka				  HttpServletResponse res and String tag.  
 * 						- delete redundant code
 * Changes at 20.12: 	- show_papers tested with my personal db,
 * by pka				  new person(-1) let me get all persons,
 * 						  changed readserviceImpl
 *  			 		- show_authors, show_reviewers update
 * Changes at 22.12: 	- show_papers changed, is complete, also with xsl
 * Changes at 27.12: 	- show_authors and show_reviewers are complete, 
 * by pka				  also with xsl  								
 **/

public class Chair extends HttpServlet 
{
	static final long serialVersionUID = 1;
	StringBuffer info = new StringBuffer();
	StringBuffer result = new StringBuffer();
	XMLHelper helper = new XMLHelper();
	StringBuffer status = new StringBuffer();
	String path = null;
	String user = null;
	Navcolumn myNavCol = null;
	String tag;
	private ReadServiceImpl read = new ReadServiceImpl();
	private DeleteServiceImpl delete = new DeleteServiceImpl();
	private InsertServiceImpl insert = new InsertServiceImpl();
	private UpdateServiceImpl update = new UpdateServiceImpl();
	Conference c = null;
	Person user_person = null;
	boolean is_abstract,is_review,is_notification,is_paper,is_final=false;
	boolean menu = false;
	
	public void doGet(HttpServletRequest req,HttpServletResponse res) 
	{
		info.delete(0,info.length());
		result.delete(0,result.length());
		String action = req.getParameter("action");
		HttpSession session= req.getSession(true);
		if (session==null)
		{
			ALogger.log.log(ERROR, "CHAIR: there is no session");
		}
		path = getServletContext().getRealPath("");
		user_person = (Person)session.getAttribute(SessionAttribs.PERSON);
		c = (Conference)session.getAttribute(SessionAttribs.CONFERENCE);
		Date now = new Date();
		if (c.getAbstract_submission_deadline()!=null)
			if (c.getAbstract_submission_deadline().compareTo(now)<0)
				is_abstract = true;
		if (c.getFinal_version_deadline()!=null)
			if (c.getFinal_version_deadline().compareTo(now)<0)
				is_final = true;
		if (c.getPaper_submission_deadline()!=null)
			if (c.getPaper_submission_deadline().compareTo(now)<0)
				is_paper = true;
		if (c.getReview_deadline()!=null)
			if (c.getReview_deadline().compareTo(now)<0)
				is_review = true;
		myNavCol = new Navcolumn(req.getSession(true));
		if (c.getName().equals(user_person.getEmail()))
			myNavCol.addExtraData("<init/>");
		user = user_person.getFirst_name() + " " + user_person.getLast_name();
		if(menu)
			myNavCol.addExtraData("<list_menu/>");
		if(ProgramFileExists())
		{
			myNavCol.addExtraData("<ConfEnd/>");
		}
		if (action.equals("invite_person"))
		{	
			invite_person(req,res,session);
		}
		if (action.equals("show_authors"))
		{
			show_authors(req,res,session);
		}
		if (action.equals("list_menu"))
		{
			list_menu(req,res,session);
		}
		if (action.equals("show_reviewers"))
		{
			show_reviewers(req,res,session);
		}
		if (action.equals("login"))
		{
			menu = false;
			login(req,res,session);	
		}
		if (action.equals("show_papers"))
		{
			show_papers(req,res,session);
		}
		if (action.equals("setup"))
		{
			setup(req,res,session);
		}
		if (action.equals("show_topics"))
		{
			show_topics(req,res,session);
		}
		if (action.equals("show_criterions"))
		{
			show_criterions(req,res,session);
		}
		if (action.equals("email"))
		{
			email(req,res,session);
		}
		if (action.equals("send_invitation"))
		{
			send_invitation(req,res,session);
		}
		if (action.equals("send_setup"))
		{
			send_setup(req,res,session);
		}
		if (action.equals("send_email"))
		{
			send_email(req,res,session);
		}
		if (action.equals("topic"))
		{
			topic(req,res,session);
		}
		if (action.equals("assign"))
		{
			assign(req,res,session);
		}
		if (action.equals("doAssign"))
		{
			doAssign(req,res,session);
		}
		if (action.equals("criterion"))
		{
			criterion(req,res,session);
		}
		if (action.equals("program"))
		{
			program(req,res,session);
		}
		if (action.equals("programCommit"))
		{
			programCommit(req,res,session);
		}
		if (action.equals("programShow"))
		{
			programShow(req,res,session);
		}
		if (action.equals("make_statistics"))
		{
			make_statistics(req,res,session);
		}
		if (action==null)
		{
			ALogger.log.log(ERROR, "CHAIR: action is NULL");
		}
	}

	public void doPost(HttpServletRequest request, HttpServletResponse response) 
	{
		doGet(request, response);
	}
			
	private void login(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{		
		tag = "login";
		info.append("<content>");
		if (is_abstract)
			info.append(XMLHelper.tagged("abstract","Abstract submission deadline terminated\n"));
		if (is_paper)
			info.append(XMLHelper.tagged("paper","Paper submission deadline terminated\n"));
		if (is_final)
		{
			info.append(XMLHelper.tagged("final","Final submission deadline terminated\n"));
		}	
		if (is_review)
		{
			info.append(XMLHelper.tagged("review","Review submission deadline terminated\n"));
			if (!ProgramFileExists())
				info.append(XMLHelper.tagged("select","Please select papers to create programm\n"));
		}
		info.append("</content>");
		info.append(XMLHelper.tagged("status", user+" , Welcome to JCOMA "));
		commit(res,tag);		
	}
	
	private void list_menu(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		menu=!menu;
		if (menu==true)
		{
			try
			{
				res.sendRedirect("/coma/Chair?action=make_statistics");
			}
			catch(IOException io)
			{
				io.printStackTrace();
			}	
		}
		else
		{
			session.setAttribute("topics",null);
			session.setAttribute("authors",null);
			session.setAttribute("reviewers",null);
			session.setAttribute("participants",null);
			session.setAttribute("criterions",null);
			session.setAttribute("papers",null);
			session.setAttribute("reports",null);
			try
			{
				res.sendRedirect("/coma/Chair?action=login");
			}
			catch(IOException io)
			{
				io.printStackTrace();
			}
		}	
	}
	
	private void make_statistics(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		tag = "statistic";
		SearchCriteria search = new SearchCriteria();
		int id = c.getId();
		Paper search_paper = new Paper(-1);
		search_paper.setConference_id(c.getId());
		search.setPaper(search_paper);
		Paper[] papers = (Paper[])read.getPaper(search).getResultObj();
		int[] role = new int[] {1,3,4,5};
		Person[] persons = (Person[])read.getPersonByRole(role,id).getResultObj();
		Topic[] topics =(Topic[])read.getTopic(-1,id).getResultObj();
		role = new int[] {4};
		Person[] authors = (Person[])read.getPersonByRole(role,id).getResultObj();
		role = new int[] {3};
		Person[] reviewers = (Person[])read.getPersonByRole(role,id).getResultObj();
		role = new int[] {5};
		Person[] participants = (Person[])read.getPersonByRole(role,id).getResultObj();
		Criterion criterion = new Criterion();
	    criterion.setConferenceId(c.getId());
	    search.setCriterion(criterion);
	    Criterion[] criterions = (Criterion[])read.getCriterion(search).getResultObj();
	    search.setReviewReport(new ReviewReport(-2));
	    search.setConference(c);
	    ReviewReport[] reports = (ReviewReport[])read.getReviewReport(search).getResultObj();
		int number_of_persons = persons.length;
		int number_of_authors = authors.length;
		int number_of_reviewers = reviewers.length;
		int number_of_topics = topics.length;
		int number_of_criterions = criterions.length;
		int number_of_participants = participants.length;
		int number_of_papers = papers.length;
		int number_of_reports = reports.length;
	    session.setAttribute("topics",topics);
		session.setAttribute("authors",authors);
		session.setAttribute("persons",persons);
		session.setAttribute("reviewers",reviewers);
		session.setAttribute("participants",participants);
		session.setAttribute("criterions",criterions);
		session.setAttribute("papers",papers);
		session.setAttribute("reports",reports);
		info.append("<content>");
		info.append(XMLHelper.tagged("persons",number_of_persons+1));
		info.append(XMLHelper.tagged("authors",number_of_authors));
		info.append(XMLHelper.tagged("reviewers",number_of_reviewers));
		info.append(XMLHelper.tagged("topics",number_of_topics));
		info.append(XMLHelper.tagged("criterions",number_of_criterions));
		info.append(XMLHelper.tagged("papers",number_of_papers));
		info.append(XMLHelper.tagged("reports",number_of_reports));
		info.append(XMLHelper.tagged("participants",number_of_participants));;
		info.append("</content>");
		info.append(XMLHelper.tagged("status","Statistics"));
		commit(res,tag);
	}
	
	private void invite_person(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		info.append(XMLHelper.tagged("content",""));
		info.append(XMLHelper.tagged("status","Please invite Person"));
		tag = "invite";
		commit(res,tag);
	}
	
	private void send_invitation(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		Person p = new Person(-1);
		String[] formular = new String[] {req.getParameter("first_name"),req.getParameter("last_name")
				,req.getParameter("email")};
		FormularChecker checker = new FormularChecker(formular);
		if (checker.check())
		{
			p.setFirst_name(formular[0]);
			p.setLast_name(formular[1]);
			p.setEmail(formular[2]);
			p.setConference_id(c.getId());
			SearchCriteria criteria = new SearchCriteria();
			criteria.setPerson(p);
			SearchResult result = read.getPerson(criteria);
			Person[] result_array = (Person[])result.getResultObj();
			if (result_array.length!=0)
			{
				info.append(XMLHelper.tagged("content",""));
				info.append(XMLHelper.tagged("status","Person " +  req.getParameter("first_name") + " " + req.getParameter("last_name")+ " is already in database"));
				tag = "invite";
				commit(res,tag);
			}
			else
			{
				Password_maker password_maker = new Password_maker(req.getParameter("first_name"),req.getParameter("last_name"),req.getParameter("email"));
				String pass = password_maker.generate_password();
				p.setPassword(pass);
				String email_formular[] = new String[3];
				if (req.getParameter("invite as").equals("author"))
				{
					p.setRole_type(4);
					email_formular[1] = "Invitation as Author for Conference " + c.getName();
				}
					
				if (req.getParameter("invite as").equals("reviewer"))
				{
					p.setRole_type(3);
					email_formular[1] = "Invitation as Reviewer for Conference " + c.getName();
				}	
				if (req.getParameter("invite as").equals("participant"))
				{
					p.setRole_type(5);
					email_formular[1] = "Invitation as Participant for Conference " + c.getName();
				}
				email_formular[2] = req.getParameter("comment").toString() + "\n"
				+"Conference Homepage: " + c.getHomepage()+"\n"
				+"your username: "+ p.getEmail()+"\n"
				+"your password: "+ p.getPassword();	
				email_formular[0]=p.getEmail();
				boolean SENDED = sendEmail(user_person.getEmail(),formular);
				tag = "invitation_send";
				if (checker.EmailCheck(2))
				{
					if (SENDED)
					{
						info.append(XMLHelper.tagged("status","E-Mail successfully send to " + req.getParameter("first_name") +" " +  req.getParameter("last_name")));
						insert.insertPerson(p);
					}
					else
					{
						info.append(XMLHelper.tagged("status","Sending problems, try again later ;-)"));
						tag = "invite";
						info.append("<content>");
					    info.append(XMLHelper.tagged("first",formular[0]));
					    info.append(XMLHelper.tagged("last",formular[1]));
					    info.append(XMLHelper.tagged("email",formular[2]));
						info.append("</content>");
					}		
				}
				else
				{
					info.append(XMLHelper.tagged("status","INVALID EMAIL ADDRESS"));	
					tag = "invite";
					info.append("<content>");
				    info.append(XMLHelper.tagged("first",formular[0]));
				    info.append(XMLHelper.tagged("last",formular[1]));
				    info.append(XMLHelper.tagged("email",formular[2]));
					info.append("</content>");
				}	
			    //info.append(XMLHelper.tagged("content",""));    
			    commit(res,tag);
			}
		}
		else 
		{
		    info.delete(0,info.length());
		    info.append("<content>");
		    info.append(XMLHelper.tagged("first",formular[0]));
		    info.append(XMLHelper.tagged("last",formular[1]));
		    info.append(XMLHelper.tagged("email",formular[2]));
			info.append("</content>");
			info.append(XMLHelper.tagged("status","Please check your textfields"));
			tag = "invite";
			commit(res,tag);
		}
	}
	
	private void criterion(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		if(req.getParameter("criterion_target").equals("add"))
		{
			tag = "criterion_add";
			info.append(XMLHelper.tagged("status","Please insert a criterion"));
			info.append(XMLHelper.tagged("content",""));
			commit(res,tag);	
		}
		
		if(req.getParameter("criterion_target").equals("save"))
		{
			String[] formular = new String[] {req.getParameter("criterion_name"),req.getParameter("criterion_description")
					,req.getParameter("criterion_value"),req.getParameter("criterion_ranking")};
			FormularChecker checker = new FormularChecker(formular);
			if (checker.check())
			{
				Criterion criterion = new Criterion(-1);
				criterion.set_conference_id(c.getId());
				criterion.setDescription(req.getParameter("criterion_description"));
				criterion.setMaxValue(Integer.parseInt(req.getParameter("criterion_value").toString()));
				criterion.setName(req.getParameter("criterion_name"));
				criterion.setQualityRating(Integer.parseInt(req.getParameter("criterion_ranking").toString()));
				insert.insertCriterion(criterion);
				setup(req,res,session);
			}
			else
			{
				tag = "criterion_add";
				info.append(XMLHelper.tagged("status","Please check your textfields"));
				info.append("<content>");
				info.append(XMLHelper.tagged("name",formular[0]));
			    info.append(XMLHelper.tagged("description",formular[1]));
			    info.append(XMLHelper.tagged("value",formular[2]));
			    info.append(XMLHelper.tagged("ranking",formular[3]));
			    info.append("</content>");
				commit(res,tag);
			}	
		}
		
		if(req.getParameter("criterion_target").equals("delete"))
		{
			int id = Integer.parseInt(req.getParameter("id").toString());
			delete.deleteCriterion(id);
			setup(req,res,session);
		}
		
		if(req.getParameter("criterion_target").equals("change"))
		{
			tag = "criterion_change";
			int id = Integer.parseInt(req.getParameter("id").toString());
			Criterion criterion = new Criterion(id);
			SearchCriteria search = new SearchCriteria();
			search.setCriterion(criterion);
			SearchResult result = new SearchResult();
			result = read.getCriterion(search);
			Criterion[] result_criterion= (Criterion[])result.getResultObj();
			info.append("<content>");
			info.append(XMLHelper.tagged("name",result_criterion[0].getName()));
		    info.append(XMLHelper.tagged("description",result_criterion[0].getDescription()));
		    info.append(XMLHelper.tagged("value",result_criterion[0].get_max_value()));
		    info.append(XMLHelper.tagged("ranking",result_criterion[0].getQualityRating()));
		    info.append(XMLHelper.tagged("id",result_criterion[0].getId()));
		    info.append("</content>");
		    info.append(XMLHelper.tagged("status","Change Criterion "+result_criterion[0].getName()));
			commit(res,tag);	
		}
		
		if(req.getParameter("criterion_target").equals("update"))
		{
			int id = Integer.parseInt(req.getParameter("id").toString());
			Criterion criterion = new Criterion(id);
			criterion.setConferenceId(c.getId());
			if (!req.getParameter("criterion_name").equals(""))
				criterion.setName(req.getParameter("criterion_name"));
			if (!req.getParameter("criterion_description").equals(""))
				criterion.setDescription(req.getParameter("criterion_description"));
			if (!req.getParameter("criterion_value").equals(""))
				criterion.set_max_value(Integer.parseInt(req.getParameter("criterion_value").toString()));
			if (!req.getParameter("criterion_ranking").equals(""))
				criterion.set_quality_rating(Integer.parseInt(req.getParameter("criterion_ranking").toString()));
			update.updateCriterion(criterion);
			setup(req,res,session);
		}		
	}
	
	private void topic(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		if(req.getParameter("topic_target").equals("add"))
		{
		    Topic[] topics_array = (Topic[])session.getAttribute("topics");	
			tag="add_topics";
			int topics = Integer.parseInt(req.getParameter("topics"));
			session.setAttribute("number_of_topics",topics);
			info.append("<content>");
			for (int i=0;i<topics_array.length;i++)
			{
				info.append(topics_array[i].toXML());
			}
			for(int i=0;i<topics;i++)
			{
				info.append("<topic_new>");
				info.append(XMLHelper.tagged("number",i));
				info.append("</topic_new>");
			}
			info.append("</content>");
			info.append(XMLHelper.tagged("status","Please insert Topic(s): "));
			commit(res,tag);
		}
		
		if(req.getParameter("topic_target").equals("save"))
		{
			int topics = Integer.parseInt(session.getAttribute("number_of_topics").toString());
			session.setAttribute("number_of_topics",null);
			for (int i=0;i<topics;i++)
			{
				if ((req.getParameter(String.valueOf(i))!=null) && !(req.getParameter(String.valueOf(i)).equals("")))
					insert.insertTopic(c.getId(),req.getParameter(String.valueOf(i)));
			}
			setup(req,res,session);
		}	
		
		if(req.getParameter("topic_target").equals("delete"))
		{
			int id = Integer.parseInt(req.getParameter("id").toString());
			delete.deleteTopic(id);
			setup(req,res,session);
		}
	}
	
	private void setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		Topic t = new Topic(-1);
	    SearchResult search_result = read.getTopic(t.getId(),c.getId());
	    Topic[] topics = (Topic[])search_result.getResultObj();	
	    Criterion criterion = new Criterion();
	    criterion.setConferenceId(c.getId());
	    SearchCriteria search = new SearchCriteria();
	    search.setCriterion(criterion);
	    search_result = read.getCriterion(search);
	    Criterion[] criterions = (Criterion[])search_result.getResultObj();	
		if (req.getParameter("target").equals("topics"))
		{
			int count = 0;
			tag="setup_topics";
			info.append(XMLHelper.tagged("status","Topic(s) Setup"));
			info.append("<content>");
			for (int i=0;i<topics.length;i++)
			{
				info.append("<topics>");
				int topic_id = topics[i].getId();
				info.append(topics[i].toXML());
				info.append("</topics>");
			}
			info.append("</content>");
			session.setAttribute("topics",topics);
			commit(res,tag);
		}

		if (req.getParameter("target").equals("conference"))
		{
			tag = "setup";
			info.append(XMLHelper.tagged("status","Conference Configuration"));
			info.append("<content>\n");
			if (c!=null)
				info.append(c.toXML(Entity.XMLMODE.DEEP));	
			if (!is_abstract)
				info.append(XMLHelper.tagged("abstract_deadline",""));
			if (!is_paper)
				info.append(XMLHelper.tagged("paper_deadline",""));
			if (!is_review)
				info.append(XMLHelper.tagged("review_deadline",""));
			if (!is_final)
				info.append(XMLHelper.tagged("final_deadline",""));
			info.append(XMLHelper.tagged("start_setup",""));
			info.append(XMLHelper.tagged("end_setup",""));
			info.append(XMLHelper.tagged("min_setup",""));
			if(topics!=null)
				info.append(XMLHelper.tagged("topic_numbers",topics.length));
			else
				info.append(XMLHelper.tagged("topic_numbers",0));
			if(criterions!=null)
				info.append(XMLHelper.tagged("criterion_numbers",criterions.length));
			else
				info.append(XMLHelper.tagged("criterion_numbers",0));
			info.append("</content>\n");
			commit(res,tag);
		}
		
		if (req.getParameter("target").equals("criteria"))
		{
			tag="criteria";
			criterion = new Criterion(-1);
			criterion.setConferenceId(c.getId());
			SearchCriteria criteria = new SearchCriteria();
		    criteria.setCriterion(criterion);
			search_result = read.getCriterion(criteria);
			Criterion[] result_criteria = (Criterion[])search_result.getResultObj();
			info.append("<content>");
			for (int i=0;i<result_criteria.length;i++)
			{
				info.append(result_criteria[i].toXML());
			}
			info.append("</content>\n");
			info.append(XMLHelper.tagged("status","Criterion Configuration"));
			commit(res,tag);
		}
		
		if (req.getParameter("target").equals("save"))
		{
			tag="save_initial";
			info.append("<content>");
			t = new Topic(-1);
			search_result = read.getTopic(t.getId(),c.getId());
			topics = (Topic[])search_result.getResultObj();	
			if (topics.length==0)
			{
				info.append("<error>");
				info.append("you have to choose at least 1 Topic\n");
				info.append("</error>");
			} 
			Criterion search_criteria = new Criterion(-1);
			search_criteria.setConferenceId(c.getId());
			SearchCriteria criteria = new SearchCriteria();
		    criteria.setCriterion(search_criteria);
			search_result = read.getCriterion(criteria);
			Criterion[] result_criteria = (Criterion[])search_result.getResultObj();	
			if (result_criteria.length==0)
			{
				info.append("<error>");
				info.append("you have to choose at least 1 Criterion\n");
				info.append("</error>");
			}
			info.append("</content>");
			info.append(XMLHelper.tagged("status","Save initial Setup"));
			commit(res,tag);
		}
		
		if(req.getParameter("target").equals("save_initial"))
		{
			if (!req.getParameter("name").equals(""))
			{
				c.setName(req.getParameter("name").toString());
				update.updateConference(c);
				session.setAttribute(SessionAttribs.CONFERENCE,c);
				try
				{
				res.sendRedirect("/coma/Chair?action=setup&target=conference");
				}
				catch(IOException io)
				{
					
				}	
			}
			else
			{
				tag="save_initial";
				info.append(XMLHelper.tagged("status","You must choose a name for the conference"));
				info.append(XMLHelper.tagged("content",""));
				commit(res,tag);
			}	
		}	
	}

	private void show_topics(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		int count = 0;
		Topic[] topics = (Topic[])session.getAttribute("topics");	
	    Paper p = new Paper(-2);
	    SearchCriteria criteria = new SearchCriteria();
	    criteria.setPaper(p);
	    SearchResult search_result = read.getPaper(criteria);
		Paper[] papers = (Paper[])search_result.getResultObj();
		tag="show_topics";
		info.append(XMLHelper.tagged("status","Topics"));
		info.append("<content>");
		for (int i=0;i<topics.length;i++)
		{
			info.append("<topics>");
			int topic_id = topics[i].getId();
			for (int j=0;j<papers.length;j++)
			{
				search_result = read.isAboutTopic(papers[j].getId(),topic_id);
				String isAbout = String.valueOf(search_result.getResultObj());
				if (isAbout.equals("true"))
					count++;
			}
			info.append(topics[i].toXML());
			info.append(XMLHelper.tagged("number_of_papers",count));
			count=0;
			info.append("</topics>");
		}
		info.append("</content>");
		session.setAttribute("topics",topics);
		commit(res,tag);
	}
	
	private void show_criterions(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
	    Criterion[] criterions = (Criterion[])session.getAttribute("criterions");
		tag="show_criterions";
		info.append(XMLHelper.tagged("status","Criterions"));
		info.append("<content>");
		for (int i=0;i<criterions.length;i++)
		{
			info.append(criterions[i].toXML());
		}
		info.append("</content>");
		commit(res,tag);
	}
	
	private void send_setup(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{ 
		GregorianCalendar calendar = null;
		if (req.getParameter("conference name")!=null)
			if (!req.getParameter("conference name").equals(""))
				c.setName(req.getParameter("conference name"));
		if (req.getParameter("homepage")!=null)
			if (!req.getParameter("homepage").equals(""))	
				c.setHomepage(req.getParameter("homepage"));
		if (req.getParameter("description")!=null)
			if (!req.getParameter("description").equals(""))
				c.setDescription(req.getParameter("description"));
		if (req.getParameter("min")!=null)
			if (!(req.getParameter("min").equals("")))
				c.setMin_review_per_paper(Integer.parseInt(req.getParameter("min")));	
		if (req.getParameter("abstract_day")!=null)
			if (!(req.getParameter("abstract_day").equals("")) && !(req.getParameter("abstract_month").equals("")) && !(req.getParameter("abstract_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("abstract_year")),
			    Integer.parseInt(req.getParameter("abstract_month"))-1,Integer.parseInt(req.getParameter("abstract_day")));
			    c.setAbstract_submission_deadline(calendar.getTime());
			}
		if (req.getParameter("final_day")!=null)
			if (!(req.getParameter("final_day").equals("")) && !(req.getParameter("final_month").equals("")) && !(req.getParameter("final_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("final_year")),
			    		Integer.parseInt(req.getParameter("final_month"))-1,Integer.parseInt(req.getParameter("final_day")));
			    c.setFinal_version_deadline(calendar.getTime());
			}
		if (req.getParameter("start_day")!=null)
			if (!(req.getParameter("start_day").equals("")) && !(req.getParameter("start_month").equals("")) && !(req.getParameter("start_year").equals("")))
			{
			calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("start_year")),
		    		Integer.parseInt(req.getParameter("start_month"))-1,Integer.parseInt(req.getParameter("start_day")));
			    c.setConference_start(calendar.getTime());
			}
		if (req.getParameter("end_day")!=null)
			if (!(req.getParameter("end_day").equals("")) && !(req.getParameter("end_month").equals("")) && !(req.getParameter("end_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("end_year")),
			    		Integer.parseInt(req.getParameter("end_month"))-1,Integer.parseInt(req.getParameter("end_day")));
			    c.setConference_end(calendar.getTime());
			}
		if (req.getParameter("paper_day")!=null)
			if (!(req.getParameter("paper_day").equals("")) && !(req.getParameter("paper_month").equals("")) && !(req.getParameter("paper_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("paper_year")),
			    		Integer.parseInt(req.getParameter("paper_month"))-1,Integer.parseInt(req.getParameter("paper_day")));
			    c.setPaper_submission_deadline(calendar.getTime());
			}
		if (req.getParameter("review_day")!=null)
			if (!(req.getParameter("review_day").equals("")) && !(req.getParameter("review_month").equals("")) && !(req.getParameter("review_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("review_year")),
			    		Integer.parseInt(req.getParameter("review_month"))-1,Integer.parseInt(req.getParameter("review_day")));
			    c.setReview_deadline(calendar.getTime());
			}
		if (req.getParameter("not_day")!=null)
			if (!(req.getParameter("not_day").equals("")) && !(req.getParameter("not_month").equals("")) && !(req.getParameter("not_year").equals("")))
			{
				calendar = new GregorianCalendar(Integer.parseInt(req.getParameter("not_year")),
			    		Integer.parseInt(req.getParameter("not_month"))-1,Integer.parseInt(req.getParameter("not_day")));
			    c.setNotification(calendar.getTime());
			}
		update.updateConference(c);
		session.setAttribute(SessionAttribs.CONFERENCE,c);
		setup(req,res,session);	
	}
	
	private void show_authors(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		Person p = null;
		Person[] result;
		boolean PAPER = false;
		if(req.getParameter("delete")!=null)
		{
			int id = Integer.parseInt(req.getParameter("id"));
			delete.deletePerson(id);
			try
			{
			res.sendRedirect("/coma/Chair?action=show_authors");
			}
			catch(IOException io)
			{
				io.printStackTrace();
			}
		}
		
		if (req.getParameter("id")==null)
		{
			tag = "showauthors";
	        result = (Person[])session.getAttribute("authors");	
		}
		else
		{
			p = new Person(Integer.parseInt(req.getParameter("id")));
			PAPER=true;
			tag = "showauthors_data";
			SearchCriteria search = new SearchCriteria();
		    search.setPerson(p);
		    SearchResult search_result = read.getPerson(search);
	        result = (Person[])search_result.getResultObj();
		}
		
		if (result==null || result.length ==0)
        {
			info.append(XMLHelper.tagged("status","No Authors available"));
        	commit(res,tag);
        }
        else
        {
        	info.append("<content>");
        	for (int i=0;i<result.length;i++)
        	{
        		p = result[i];
        		info.append(p.toXML());
        	}
        	if (PAPER)
        	{
        		try
        		{
        			Paper[] personPaper = p.getPapers();
	        		for (int i=0;i<personPaper.length;i++)
	        		{
	        			info.append(personPaper[i].toXML());
	        		}
        		}
        		catch(Exception e)
        		{
        			System.out.println(e.toString());
        		}
	        		
        	}
        	info.append("</content>");
        	if (PAPER)
        		info.append(XMLHelper.tagged("status","Statistic Author "+ p.getFirst_name()+" "+p.getLast_name()));	
        	else
        		info.append(XMLHelper.tagged("status","Authors"));	
        	commit(res,tag);
        }
	}

	private void show_reviewers(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		Person p = null;
		SearchCriteria search = new SearchCriteria();
		Person[] result;
		int id = 0;
		if(req.getParameter("delete")!=null)
		{
			id = Integer.parseInt(req.getParameter("id"));
			delete.deletePerson(id);
			try
			{
			res.sendRedirect("/coma/Chair?action=show_reviewers");
			}
			catch(IOException io)
			{
				
			}
		}
		if (req.getParameter("id")==null)
		{
			tag = "showreviewers";
	        result = result = (Person[])session.getAttribute("reviewers");		
		}
		else
		{
			id = Integer.parseInt(req.getParameter("id"));
			p = new Person(id);
			tag = "showreviewers_data";
		    search.setPerson(p);
		    SearchResult search_result = read.getPerson(search);
	        result = (Person[])search_result.getResultObj();	        
		}
		if (result==null || result.length ==0)
        {
			info.append(XMLHelper.tagged("status","No Reviewers available"));
        	commit(res,tag);
        }
		else
        {
			ReviewReport RR = new ReviewReport();
	        RR.set_reviewer_id(id);
	        search.setReviewReport(RR);
	        ReviewReport[] result_reports = (ReviewReport[])read.getReviewReport(search).getResultObj();
        	info.append("<content>");
        	for (int i=0;i<result.length;i++)
        	{
        		p = result[i];
        		info.append(p.toXML());
        	}
        	for (int i=0;i<result_reports.length;i++)
        	{
        		RR = result_reports[i];
        		info.append(RR.toXML());
        	}
        	info.append("</content>");
        	info.append(XMLHelper.tagged("status","Reviewers"));
        	commit(res,tag);
        }
	}
	
	private void show_papers(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
        tag = "show_papers";
        SearchCriteria search = new SearchCriteria();
        Paper p; 
        Paper search_paper = new Paper(-1);
        search_paper.setConference_id(c.getId());
		search.setPaper(search_paper);
		Paper[] result= (Paper[])read.getPaper(search).getResultObj();
        if (result==null || result.length ==0)
        {
        	info.append(XMLHelper.tagged("status","No Papers available"));
        	commit(res,tag);
        }
        else
        {
        	info.append("<content>");
        	
        	search = new SearchCriteria();
        	System.out.println(result.length);
        	for (int i=0;i<result.length;i++)
        	{
        		info.append("<paperPlus>");
        		p = result[i];
            	search.setPaper(p);
            	Finish[] f = (Finish[])read.getFinalData(search,0,c.getId()).getResultObj();
            	if (f.length!=0)
            		info.append(XMLHelper.tagged("avg",f[0].getAvgGrade()));
        		info.append(p.toXML(Entity.XMLMODE.DEEP));
        		info.append("</paperPlus>");
        	}
        	info.append("</content>\n");
        	info.append(XMLHelper.tagged("status","Papers"));
        	commit(res,tag);	
        }
}
	
	private void email(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		if (req.getParameter("email")!=null)
		{
			info.append("<content>");
			info.append(XMLHelper.tagged("recv",req.getParameter("email")));
			info.append("</content>");
		}
		else
		{
			info.append(XMLHelper.tagged("content",""));
		}
		info.append(XMLHelper.tagged("status","Please write an email"));
		tag="email";
		commit(res,tag);
	}
	
	private void send_email(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{		
		String[] formular = new String[] {req.getParameter("Recv"),
				req.getParameter("Subj"),req.getParameter("Cont")};
		FormularChecker checker = new FormularChecker(formular);
		boolean VALID = checker.EmailCheck(0);
		boolean SENDED = false;
		if (VALID)
		{
			SMTPClient MyE = new SMTPClient(user_person.getEmail(),
				formular[0],formular[1],formular[2]);
			SENDED=MyE.send();		
			if(SENDED)
			{
				info.append(XMLHelper.tagged("content",""));
				info.append(XMLHelper.tagged("status","E-Mail successfully send to " + formular[0] +" at " + MyE.getDate()));
				String tag = "email";
				commit(res,tag);
			}
		}	
		if(!SENDED || !VALID)
		{
			if(VALID && !SENDED)
				info.append(XMLHelper.tagged("status","SENDING PROBLEMS : INFORM YOUR ADMIN"));
			if(!VALID)
				info.append(XMLHelper.tagged("status","ENTER A VALID EMAIL ADDRESS"));
			info.append("<content>");
			info.append(XMLHelper.tagged("subj",formular[1]));
			info.append(XMLHelper.tagged("recv",formular[0]));
			info.append(XMLHelper.tagged("cont",formular[2]));
			info.append("</content>");
			tag = "email";
			commit(res,tag);
		}
	}
	
	private void assign(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
		int count=0;
		Paper p =null;
		int PaperID;
		if(req.getParameter("id")!= null)
		{
			PaperID = Integer.parseInt(req.getParameter("id"));
			session.setAttribute("PaperID",req.getParameter("id"));
			p = new Paper(PaperID);
		}
		else
		{
			PaperID = Integer.parseInt(((String)session.getAttribute("PaperID")));
			p = new Paper(PaperID);
		}
        SearchCriteria search = new SearchCriteria();
        search.setPaper(p);
        SearchResult search_resultP = read.getPaper(search); 
        Paper[] resultP = (Paper[])search_resultP.getResultObj(); 
        ReviewReport RR = new ReviewReport();
        RR.setPaperId(PaperID);    
        search.setReviewReport(RR);
        SearchResult search_resultR = read.getReviewReport(search); 
        ReviewReport[] resultR = (ReviewReport[])search_resultR.getResultObj();
        info.append("<content>");
        tag = "assign";
        if (resultP==null || resultP.length ==0)
        {
        	info.append("</content>");
        	info.append(XMLHelper.tagged("status","No Papers available"));
        	commit(res,tag);
        }
        else
        {
        	for (int i=0;i<resultP.length;i++)
        	{
        		p = resultP[i];
        		info.append(p.toXML());
        	}
        	for (int i=0;i<resultR.length;i++)
        	{
        		RR = resultR[i];
        		if(RR.isEdited());
        			info.append(RR.toXML());
        	}	
	        Person p1 = new Person(-1);
	        p1.setRole_type(3);
			SearchCriteria search1 = new SearchCriteria();
	        search.setPerson(p1);
	        SearchResult search_result1 = read.getPerson(search);
	        Person[] result1 = (Person[])search_result1.getResultObj();  
	   	 	if (result1==null || result1.length ==0)
	        {
	   	 		info.append("</content>");
	   	 		info.append(XMLHelper.tagged("status","No Reviewers available"));
	        	commit(res,tag);
	        }
	        else
	        {
	        	Vector<String> CheckedReviewers = new java.util.Vector<String>();
	          	for (int i=0;i<result1.length;i++)
	        	{    
	          		info.append("<personplus>");
	        		p1 = result1[i];
	        		ReviewReport RR1 = new ReviewReport();
	        		RR1.set_reviewer_id(p1.getId());
	        		SearchCriteria criteria = new SearchCriteria();
	        		criteria.setReviewReport(RR1);
	        		SearchResult result =new SearchResult();
	        		result = read.getReviewReport(criteria);
	        		ReviewReport[] result_RR = (ReviewReport[])result.getResultObj();
	        		info.append(XMLHelper.tagged("number_of_ReviewReports",result_RR.length));
	        		info.append(p1.toXML());
		        	for (int j=0;j<resultR.length;j++)
		        	{
		        		if(result1[i].getId() == resultR[j].get_reviewer_id())
		        		{      
		        			count++;
		        			info.append(XMLHelper.tagged("checked","checked"));
		        			CheckedReviewers.addElement(String.valueOf(resultR[j].get_reviewer_id()));
	        			}
		        	}        	
	        		info.append("</personplus>");
	        	}
	          	if (c.getMin_review_per_paper()>count)
	          		info.append(XMLHelper.tagged("more",c.getMin_review_per_paper()-count));
	          	if (c.getMin_review_per_paper()==count)
	          		info.append(XMLHelper.tagged("even",0));
	          	if (c.getMin_review_per_paper()<count)
	          		info.append(XMLHelper.tagged("already",count-c.getMin_review_per_paper()));
	        	session.setAttribute("CheckedReviewers",CheckedReviewers);
	        	info.append("</content>");
	        	info.append(XMLHelper.tagged("status","Assignment"));
	        	commit(res,tag);
	        }
        }
 	}
        
	private void doAssign(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{
	int PaperID = Integer.parseInt((String)session.getAttribute("PaperID"));
	String[] NewReviewerIDs = req.getParameterValues("CB");
	Vector OldCheckedReviewers =(Vector)session.getAttribute("CheckedReviewers");
	SearchCriteria search = new SearchCriteria();
	SearchResult result = new SearchResult();
	session.setAttribute("CheckedReviewers",null);
	tag = "assign";
	if(NewReviewerIDs != null)
	{
		for(int j=0;j<OldCheckedReviewers.size();j++)
		{
			boolean toDelete=true;
			for(int i=0;i<NewReviewerIDs.length;i++)
			{
				if (((String)OldCheckedReviewers.elementAt(j)).equals(NewReviewerIDs[i]))
				{
					NewReviewerIDs[i]=null;
					toDelete=false;
					break;				
				}	
			}
			if(toDelete)
			{
				int id = Integer.parseInt(OldCheckedReviewers.elementAt(j).toString());
				ReviewReport RR = new ReviewReport();
				RR.setReviewerId(id);
				RR.setPaperId(PaperID);
				Paper p = new Paper(PaperID);
				search = new SearchCriteria();
				search.setPaper(p);
				p = ((Paper[])read.getPaper(search).getResultObj())[0];
				p.setState(0);
				update.updatePaper(p);
				search.setReviewReport(RR);
				result = read.getReviewReport(search);
				ReviewReport[] Review_result = (ReviewReport[])result.getResultObj();
				Rating r = new Rating();
				r.setReviewReportId(Review_result[0].getId());
				delete.deleteReviewReport(Review_result[0].getId());
				search.setRating(r);
				result = read.getRating(search);
				Rating[] R = (Rating[])result.getResultObj();
				for(int i=0;i<R.length;i++){
					delete.deleteRating(R[i].get_review_id(),R[i].get_criterion_id());
				}		
			}	
		}
	    for(int i=0;i<NewReviewerIDs.length;i++)
	    {
	    	if(NewReviewerIDs[i] != null)
	    	{
	    		Paper p = new Paper(PaperID);
				search = new SearchCriteria();
				search.setPaper(p);
				p = ((Paper[])read.getPaper(search).getResultObj())[0];
				p.setState(1);
				update.updatePaper(p);
	    		ReviewReport RR = new ReviewReport(-1);
	    		RR.set_paper_Id(PaperID);   
	    		RR.set_reviewer_id(Integer.parseInt(NewReviewerIDs[i]));
	    		insert.insertReviewReport(RR);
				search.setReviewReport(RR);
				result = read.getReviewReport(search);
				RR = ((ReviewReport[])result.getResultObj())[0];
	    		Criterion cr = new Criterion();
	    		cr.set_conference_id(c.getId());
	    		search.setCriterion(cr);
	    		result = read.getCriterion(search);
				Criterion[] C = (Criterion[])result.getResultObj();
				for(int j=0;j<C.length;j++)
				{
		    		Rating R = new Rating();
		    		R.setReviewReportId(RR.getId());
			    		R.setCriterionId(C[j].getId());
					insert.insertRating(R);
				}	
	    	}
	    }
	}
	else
	{
		for(int j=0;j<OldCheckedReviewers.size();j++)
		{
			int id = Integer.parseInt(OldCheckedReviewers.elementAt(j).toString());
			ReviewReport RR = new ReviewReport();
			RR.setReviewerId(id);
			RR.setPaperId(PaperID);
			Paper p = new Paper(PaperID);
			search = new SearchCriteria();
			search.setPaper(p);
			p = ((Paper[])read.getPaper(search).getResultObj())[0];
			p.setState(0);
			update.updatePaper(p);
			search.setReviewReport(RR);
			result = read.getReviewReport(search);
			ReviewReport[] Review_result = (ReviewReport[])result.getResultObj();
			delete.deleteReviewReport(Review_result[0].getId());
			Rating r = new Rating();
			r.set_report_id(Review_result[0].getId());
			search.setRating(r);
			result = read.getRating(search);
			Rating[] R = (Rating[])result.getResultObj();
			for(int i=0;i<R.length;i++)
			{
				delete.deleteRating(R[i].get_review_id(),R[i].get_criterion_id());
			}
		}
	}
	assign(req,res,session);
	}
	
	private void programCommit(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{	
		SearchCriteria search = new SearchCriteria();
		ReadServiceImpl readService = new ReadServiceImpl();
		String[] NewSelectionIDs = req.getParameterValues("CB");
		Vector OldCheckedSelection =(Vector)session.getAttribute("SelectedPapers");
		session.setAttribute("SelectedPapers",null);
		if(NewSelectionIDs != null)
		{
			for(int j=0;j<OldCheckedSelection.size();j++)
			{
				boolean toUpdate=true;
				for(int i=0;i<NewSelectionIDs.length;i++)
				{
					if (((String)OldCheckedSelection.elementAt(j)).equals(NewSelectionIDs[i]))
					{
						NewSelectionIDs[i]=null;
						toUpdate=false;
						break;				
					}	
				}
				if(toUpdate)
				{
					Paper p = new Paper(Integer.parseInt((String)OldCheckedSelection.elementAt(j)));				
				    search.setPaper(p);
				    SearchResult search_resultP = readService.getPaper(search); 
				    p = ((Paper[])search_resultP.getResultObj())[0];
				    p.setState(4);				
					update.updatePaper(p);
				}	
			}
		    for(int i=0;i<NewSelectionIDs.length;i++)
		    {
		    	if(NewSelectionIDs[i] != null)
		    	{
					Paper p = new Paper(Integer.parseInt(NewSelectionIDs[i]));				
				    search.setPaper(p);
				    SearchResult search_resultP = readService.getPaper(search); 
				    p = ((Paper[])search_resultP.getResultObj())[0];
				    p.setState(3);	
					update.updatePaper(p);	
		    	}
		    }
		}
		else
		{
			for(int j=0;j<OldCheckedSelection.size();j++)
			{
				Paper p = new Paper(Integer.parseInt((String)OldCheckedSelection.elementAt(j)));				
			    search.setPaper(p);
			    SearchResult search_resultP = readService.getPaper(search); 
			    p = ((Paper[])search_resultP.getResultObj())[0];
			    p.setState(4);		
				update.updatePaper(p);
			}
		}
		info.append(XMLHelper.tagged("status","Changes committed: "));
		program(req,res,session);
 	}
	
	private boolean sendEmail(String SenderAdress, String[] formular){
		boolean SENDED = false;
		SMTPClient MyE = new SMTPClient(SenderAdress,
						formular[0],formular[1],formular[2]);
		SENDED=MyE.send();	
		return SENDED;
	}
	
	private boolean ProgramFileExists(){
		File Prog = new File(path + "/papers","Program-"+c.getName()+".txt");
		System.out.println(Prog.exists());
	   return Prog.exists();
	}
	
	private boolean WriteFiles(StringBuffer ProgramXML,StringBuffer ProgramTXT){
    	try{
		File TXTFile = new File(path + "/papers","Program-"+c.getName()+".txt");
			TXTFile.createNewFile();
			RandomAccessFile output = new RandomAccessFile( TXTFile, "rw" );
			String s = ProgramTXT.toString();
			byte[] b = s.getBytes();
			output.write(b);
			output.close();
			
		File XMLFile = new File(path + "/papers","Program-"+c.getName()+".xml");
			XMLFile.createNewFile();
			output = new RandomAccessFile( XMLFile, "rw" );
			s = "<?xml version='1.0' encoding='utf-8'?>"+"\n"+ProgramXML.toString();
			b = s.getBytes();
			output.write(b);
			output.close();
			return true;
    	}
    	
    	catch(IOException E){
    		E.printStackTrace();
    		return false;}
    	}
	
	
	private void sendProgram(){
		Person p= new Person(-1);
		p.setConference_id(c.getId());
		SearchCriteria search = new SearchCriteria();
        ReadServiceImpl readService = new ReadServiceImpl();
        search.setPerson(p);
        SearchResult search_result = readService.getPerson(search);
        Person[] P = (Person[])search_result.getResultObj();
        String[] formular = new String[]{"","Program of the Conference "+c.getName(),c.getName()+"\n"};      
        for(int i=0;i<P.length;i++){
        	formular[0] += P[i].getEmail()+";";
        }
        sendEmail(user_person.getEmail(),formular);
	}

	private void programShow(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{	String tag="proshow";
		StringBuffer program = new StringBuffer();
		SearchCriteria search = new SearchCriteria();
		Paper p = new Paper(-1);
		p.setState(3);
		search.setPaper(p);
		SearchResult search_resultP = read.getPaper(search);
		int Selection = ((Paper[])search_resultP.getResultObj()).length;
		long ConfDuration = c.getConference_end().getTime() - c.getConference_start().getTime();
		int Days = ((int)ConfDuration/86400000)+1;
		int SemPerDay = Selection/Days;
		int RestSem  = Selection%Days;
		String[] Times = new String[]{"09:00-10:00","10:00-11:00","11:00-12:00","13:00-14:00","14:00-15:00","15:00-16:00","16:00-17:00","17.00-18:00"};
		int MaxChoice = Days*Times.length;
		if(MaxChoice >= Selection){
		int SortBy =3;
        SearchResult search_result = read.getFinalData(search,SortBy,c.getId()); 
        Finish[] result = (Finish[])search_result.getResultObj();
        Finish f = new Finish();
        if (result==null || result.length ==0)
        {
        	info.append(XMLHelper.tagged("status","No Papers available"));
        	commit(res,tag);
        }
        else
        {
        	info.append("<content>");
        	int cnt=0;
        	int left =1;
        	if(Days >1 && Selection >8)
        	{
        		for(int j=0;j<Days;j++)
        		{
        			info.append("<day date='"+(j+1)+"'>");
        			program.append("DAY "+(j+1)+": ");

        			long dateStart = c.getConference_start().getTime();
        			Date day = new Date(dateStart+j*86400000+32400000);
        			program.append(day+"\n");
                  	info.append(XMLHelper.tagged("date",day));
		        	for (int i=0;i<SemPerDay+left;i++)
		        	{	System.out.println(cnt);
		        		f = result[cnt];
		        		f.setMyTime(Times[i]);
		        		info.append(f.toXML(Entity.XMLMODE.DEEP));
		        		program.append("Time: "+f.getmyTime()+"\n");
		        		program.append("	Author: "+f.getAuthor()+"\n");
		        		program.append("	Title: "+f.getTitle()+"\n");
		        		program.append("---------------------------\n");
		        		cnt++;        	
		        	}
		        	program.append("---------------------------\n"+"\n");
		        	info.append("</day>");
		        	if(j==RestSem-1)
		        		left=0;
        		}
        	}
        	else
        	{
        		info.append("<day date='"+1+"'>");
    			program.append("DAY 1: ");
    			long dateStart = c.getConference_start().getTime();
    			Date day = new Date(dateStart+5184000+1944000);
    			program.append(day+"\n"+"\n");
            	for (int i=0;i<Selection;i++)
            	{	
            		f = result[cnt];
            		f.setMyTime(Times[i]);
            		info.append(f.toXML(Entity.XMLMODE.DEEP));
	        		program.append("Time: "+f.getmyTime()+"\n");
	        		program.append("	Author: "+f.getAuthor()+"\n");
	        		program.append("	Title: "+f.getTitle()+"\n");
	        		program.append("---------------------------\n");
            		cnt++;        	
            	}           	

            	info.append(XMLHelper.tagged("date",c.getConference_start()));
            	info.append("</day>");
        	}
        info.append("</content>");
        

    
    	if(req.getParameter("finish") != null){
    		WriteFiles(info,program);
    	}
        }
		info.append(XMLHelper.tagged("status","Program successfully created "));
    	commit(res,tag);	
		}
		else{
				
				session.setAttribute("status","failed");
				program(req,res,session);
		}
 	}
	
	private void program(HttpServletRequest req,HttpServletResponse res,HttpSession session)
	{	
		String tag ="program";
		Vector<String> SelectedPapers = new java.util.Vector<String>();
        SearchCriteria search = new SearchCriteria();
        ReadServiceImpl readService = new ReadServiceImpl();
        int SortBy =-1;
        if(req.getParameter("sort") != null)
        	SortBy = Integer.parseInt(req.getParameter("sort"));
        SearchResult search_result = readService.getFinalData(search,SortBy,c.getId()); 
        Finish[] result = (Finish[])search_result.getResultObj();
        Finish f = new Finish();
        if (result==null || result.length ==0)
        {
        	info.append(XMLHelper.tagged("status","No Papers available"));
        	commit(res,tag);
        }
        else
        {
        	info.append("<content>");
        	for (int i=0;i<result.length;i++)
        	{
        		f = result[i];
        		info.append(f.toXML(Entity.XMLMODE.DEEP));
        		if(f.getState() == 3)
        			SelectedPapers.addElement(String.valueOf(f.getPaperId())); 	
        	}
        	info.append("</content>");
        	if(session.getAttribute("status") ==  null){
        		info.append(XMLHelper.tagged("status","Papers"));
        	}else
        	info.append(XMLHelper.tagged("status","Extend Conference Days or choose less papers"));
        	session.setAttribute("SelectedPapers",SelectedPapers);
        	commit(res,tag);	   
 	}
	}
	
	private void commit(HttpServletResponse res, String tag) 
	{
	    try
	    {
		    PrintWriter out = res.getWriter();
			res.setContentType("text/html; charset=ISO-8859-1");
			helper.addXMLHead(result);
			result.append("<result>\n");
			result.append(myNavCol+"\n");
			result.append("<" + tag + ">\n");
			result.append(info.toString());
			result.append("</" +tag + ">\n");
			result.append("</result>\n");
			System.out.println(result.toString());
			String xslt = path + "/style/xsl/chair.xsl";
		    StreamSource xmlSource = new StreamSource(new StringReader(result.toString()));
			StreamSource xsltSource = new StreamSource(xslt);
			XMLHelper.process(xmlSource, xsltSource, out);
			out.flush();
	    }
	    catch(IOException e)
	    {
	        e.printStackTrace();
	    }
	}
}

