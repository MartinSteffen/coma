
package coma.entities;

import java.util.Date;


/**
 * Created on 17.12.2004 <a href="mailto:mal@informatik.uni-kiel.de">Mohamed Z.
 * Albari </a>
 */

public class Message extends Entity{
	
	public int id;
	public int forum_id;
	public int reply_to;
	public int sender_id;
	public Date send_time;
	public String subject;
	public String text;
	
		
	/**
	 * @return Returns the forum_id.
	 */
	public int getForum_id() {
		return forum_id;
	}
	/**
	 * @param forum_id The forum_id to set.
	 */
	public void setForum_id(int forum_id) {
		this.forum_id = forum_id;
	}
	/**
	 * @return Returns the id.
	 */
	public int getId() {
		return id;
	}
	/**
	 * @param id The id to set.
	 */
	public void setId(int id) {
		this.id = id;
	}
	/**
	 * @return Returns the reply_to.
	 */
	public int getReply_to() {
		return reply_to;
	}
	/**
	 * @param reply_to The reply_to to set.
	 */
	public void setReply_to(int reply_to) {
		this.reply_to = reply_to;
	}
	/**
	 * @return Returns the send_time.
	 */
	public Date getSend_time() {
		return send_time;
	}
	/**
	 * @param send_time The send_time to set.
	 */
	public void setSend_time(Date send_time) {
		this.send_time = send_time;
	}
	/**
	 * @return Returns the sender_id.
	 */
	public int getSender_id() {
		return sender_id;
	}
	/**
	 * @param sender_id The sender_id to set.
	 */
	public void setSender_id(int sender_id) {
		this.sender_id = sender_id;
	}
	/**
	 * @return Returns the subject.
	 */
	public String getSubject() {
		return subject;
	}
	/**
	 * @param subject The subject to set.
	 */
	public void setSubject(String subject) {
		this.subject = subject;
	}
	/**
	 * @return Returns the text.
	 */
	public String getText() {
		return text;
	}
	/**
	 * @param text The text to set.
	 */
	public void setText(String text) {
		this.text = text;
	}
	/* (non-Javadoc)
	 * @see coma.entities.Entity#toXML(coma.entities.Entity.XMLMODE)
	 */
	public CharSequence toXML(XMLMODE mode) {
		// TODO Auto-generated method stub
		return null;
	}
	
	
}
