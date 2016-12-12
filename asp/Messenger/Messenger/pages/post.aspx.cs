using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Data;

namespace Messenger.pages
{
    public partial class post : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            foreach (DataListItem dListItem in dlistPosts.Items)
            {
                Image img = (Image)dListItem.FindControl("imgPersonPicture");
                img.Attributes.Add("style", "height: 2rem; width: 2rem;");
            }

            btnNewPost.Attributes.Add("disabled", "false");
            lblGroupData.Text = Session["name"].ToString();

            if (IsPostBack)
            {
                dlistPosts.DataBind();
                dListMembers.DataBind();
            }
        }

        protected void btnHome_Click(object sender, EventArgs e)
        {
            Response.Redirect("Groups", false);
        }

        protected void btnNewPost_Click(object sender, EventArgs e)
        {
            if(txtMessager.Text.Trim() != "") {
                try
                {
                    String strPersonID = "";
                    String strGroupID = "";
                    String strFileName = fileVideo.FileName;

                    sqlChanger.SelectCommand = "SELECT person.person_id, groups.groups_id FROM ((person INNER JOIN people_group ON person.person_id = people_group.person_id) INNER JOIN groups ON people_group.groups_id = groups.groups_id) WHERE ([person.fName] = '" + Session["fName"] + "') AND ([person.lName] = '" + Session["lName"] + "') AND ([person.birthday] = #" + Session["bDay"] + "#) AND ([groups.name] = '" + Session["name"] + "')";
                    DataView dvSql = (DataView)(sqlChanger.Select(DataSourceSelectArguments.Empty));
                    foreach (DataRowView rowView in dvSql)
                    {
                        strGroupID = rowView["groups_id"].ToString();
                        strPersonID = rowView["person_id"].ToString();
                    }

                    if (strFileName == "")
                    {
                        sqlChanger.InsertCommand = "INSERT INTO [people_group] ([groups_id], [person_id], [message], [posted]) VALUES (" + strGroupID + ", " + strPersonID + ", '" + txtMessager.Text + "', #" + DateTime.Today + "#)";
                        sqlChanger.Insert();
                    }
                    else
                    {
                        if (fileVideo.HasFile)
                        {
                            try
                            {
                                if (fileVideo.PostedFile.ContentType == "video/mp4")
                                {
                                    if (fileVideo.PostedFile.ContentLength <= 20000000)
                                    {
                                        if (fileVideo.FileName.IndexOf('.') <= 4)
                                        {
                                            Random ran = new Random();
                                            strFileName = strFileName.Insert(strFileName.IndexOf('.'), ran.Next(0, 9999).ToString());

                                            fileVideo.SaveAs(Server.MapPath("~/uploads/" + strFileName));
                                            sqlChanger.InsertCommand = "INSERT INTO [people_group] ([groups_id], [person_id], [message], [posted], [video]) VALUES (" + strGroupID + ", " + strPersonID + ", '" + txtMessager.Text + "', #" + DateTime.Today + "#, '"+strFileName+"')";
                                            sqlChanger.Insert();
                                        }
                                        else
                                        {
                                            lblError.Text = "Bad file name";
                                        }
                                    }
                                    else
                                    {
                                        lblError.Text = "File is to big";
                                    }
                                }
                                else
                                {
                                    lblError.Text = "Wrong file type";
                                }
                            }
                            catch (Exception)
                            {
                                lblError.Text = "Sorry bad file";
                            }
                        }
                        else
                        {
                            lblError.Text = "Sorry bad file";
                        }
                    }
                    
                    txtMessager.Text = "";
                }
                catch (Exception)
                {
                    txtMessager.Focus();
                    txtMessager.Text = "";
                }
            } else {
                txtMessager.Focus();
                txtMessager.Text = "";
            }
        }

        protected void btnPostDelete_Click(object sender, EventArgs e)
        {
            Button btn = (Button)sender;
            sqlChanger.DeleteCommand = "DELETE FROM [people_group] WHERE [people_group_id] = " + btn.Text + "";
            sqlChanger.Delete();
        }
    }
}