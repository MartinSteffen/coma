package coma.servlet.util;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.Calendar;

/**
Responsible for sending EMAILS.
The mail server problem still not solved.
if anybody has got concrete knowledge idea, please mail.
No Changes since 12.12.04
@author hbra@informatik.uni-kiel.de
*/

public class SMTPClient {
	
	private BufferedReader input;
	private PrintWriter output;
	private String server;
	private String client;
	private String senderAddress;
	private String recipientAddress;
	private Calendar date;
	private String subject;
	private String message;


	public SMTPClient(String smtpServerHost,String senAddress,String anrecAddress, String aSubject, String aMessage) {
		server = smtpServerHost;
		recipientAddress= anrecAddress;
		subject = aSubject;
		message = aMessage;
		date = Calendar.getInstance();
		senderAddress = senAddress;
	}
	
	public boolean send(){
		try{

	
		client = senderAddress.substring(senderAddress.indexOf( "@")+ 1,senderAddress.length());
		sendMessage(this.toString());
		return true;}
		catch(Exception e)
		{
			e.printStackTrace();
			return false;}
		
	}
	
	private void sendMessage(String message){
		connect();
		initiate();
		output.print(message);
		output.flush();
		end();
		disconnect();
	}
	private void connect() {
	try {

		Socket socket  = new Socket(server,25);
		input = new BufferedReader(new InputStreamReader(socket.getInputStream() ) );
		output = new PrintWriter(
				new BufferedWriter(
						new OutputStreamWriter(
						socket.getOutputStream() ) ));
	
	}catch( IOException e ){
		e.printStackTrace();
		}
	
	}
	private void disconnect() {
		try {
			input.close();
			output.close();
		}
		catch(IOException e){
			e.printStackTrace();
		}
	}
	private void initiate() {
		String response = null;
		try {
			output.print("Helo"+ client +"\r\n");
			output.print("MAIL FROM:" + senderAddress +"\r\n");
			output.print("RCPT TO:" + recipientAddress +"\r\n");
			output.print("DATA\r\n");
			output.flush();
		}
		catch(Exception e) {
		   e.printStackTrace();
			
		}
		
	}
	
	private void end() {
		String response = null;
		try {
			output.print( "\r\n.\r\n");
			output.print( "Quit\r\n");
			output.flush();
			response = input.readLine();
		}
		catch(IOException e) {
			e.printStackTrace();
	}
		

			
		}
	
public String toString() {
	String temp =
	"From: "+ senderAddress +"\n" +
	"To: "+  recipientAddress +"\n"+
	"Subject: "+ subject +"\n"+
	"Date: " + date.getTime().toString()+"\n\n"+
	 message;
	return temp;
}

public String getDate() {
	return date.getTime().toString();

}
		

}
